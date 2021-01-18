<?php declare(strict_types = 1);

namespace AlesWita\VisualPaginator;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Application\UI\Template;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\InvalidArgumentException;
use Nette\Utils\Paginator;
use Nette\Utils\Strings;

/**
 * @property-read Template $template
 */
final class VisualPaginator extends Control
{

	public const SESSION_SECTION = 'visual-paginator';

	/** @persistent */
	public ?int $page = null;

	public bool $ajax = false;

	public bool $canSetItemsPerPage = false;

	public ?string $itemsPerPageRepository = null;

	/** @var array<int, string> */
	public array $itemsPerPageList = [
		10 => '10',
		20 => '20',
		30 => '30',
		40 => '40',
		50 => '50',
		100 => '100',
	];

	/** @var array<string, string>  */
	public array $messages = [
		'send' => 'Send',
		'itemsPerPage' => 'Items per page',
	];

	public string $templateFile = __DIR__ . '/templates/normal.latte';

	/** @var array<callable> */
	public array $onPaginate = [];

	private int $itemsPerPage = 10;

	private Paginator $paginator;

	private SessionSection $sessionSection;

	public function __construct(Session $session)
	{
		$this->paginator = new Paginator();
		$this->sessionSection = $session->getSection(self::SESSION_SECTION);
	}

	public function setItemsPerPage(int $itemsPerPage): self
	{
		if (!$this->canSetItemsPerPage) {
			throw new InvalidArgumentException(self::class . '::setItemsPerPage(): can not set items per page. You can enabled it by set $canSetItemsPerPage to true.');
		}

		if (!array_key_exists($itemsPerPage, $this->itemsPerPageList)) {
			throw new InvalidArgumentException(self::class . '::setItemsPerPage(): $itemsPerPageList has not key "' . $itemsPerPage . '".');
		}

		$this->itemsPerPage = $itemsPerPage;
		$this->sessionSection->offsetSet($this->getSessionRepository(), $itemsPerPage);
		return $this;
	}

	public function setItemCount(int $count): self
	{
		$this->paginator->setItemCount($count);
		return $this;
	}

	public function getOffset(): int
	{
		return $this->paginator->getOffset();
	}

	public function getItemsPerPage(): int
	{
		return $this->paginator->getItemsPerPage();
	}

	/**
	 * @param array<mixed> $params
	 */
	public function loadState(array $params): void
	{
		parent::loadState($params);

		if ($this->sessionSection->offsetExists($this->getSessionRepository())) {
			$this->setItemsPerPage($this->sessionSection->offsetGet($this->getSessionRepository()));

			if (!$this->canSetItemsPerPage) {
				$this->sessionSection->offsetUnset($this->getSessionRepository());
			}
		}

		$this->paginator->setPage($this->page ?? 1);
		$this->paginator->setItemsPerPage($this->itemsPerPage ?? (int) array_key_first($this->itemsPerPageList));
		$this->page = $this->paginator->getPage();
		$this->itemsPerPage = $this->paginator->getItemsPerPage();

		$this['itemsPerPage']->setDefaults([
			'itemsPerPage' => $this->itemsPerPage,
		]);
	}

	public function render(): void
	{
		$this->template->steps = Helpers::createSteps($this->paginator);
		$this->template->itemsPerPage = $this->canSetItemsPerPage;
		$this->template->paginator = $this->paginator;
		$this->template->ajax = $this->ajax;

		$this->template->setFile($this->templateFile);
		$this->template->render();
	}

	protected function createComponentItemsPerPage(): Form
	{
		$form = new Form();

		$form->addSelect('itemsPerPage', $this->messages['itemsPerPage'], $this->itemsPerPageList)
			->setRequired();

		$form->addSubmit('send', $this->messages['send']);

		$form->onSuccess[] = function (Form $form, array $values): void {
			$this->setItemsPerPage($values['itemsPerPage']);
			Helpers::callEvents($this->onPaginate);

			if (!$this->presenter->isAjax()) {
				$this->redirect('this');
			}
		};

		return $form;
	}

	public function handlePaginate(): void
	{
		Helpers::callEvents($this->onPaginate);

		if (!$this->presenter->isAjax()) {
			$this->redirect('this');
		}
	}

	private function getSessionRepository(): string
	{
		$repository = null;

		if ($this->itemsPerPageRepository !== null) {
			$repository = $this->itemsPerPageRepository;
		}

		if ($repository === null) {
			$repository = $this->presenter->getName() ?? 'default';
		}

		return Strings::lower($repository);
	}

}
