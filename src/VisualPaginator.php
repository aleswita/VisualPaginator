<?php declare(strict_types = 1);

namespace AlesWita\Components;

use Nette\InvalidArgumentException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\Paginator;
use Nette\Http\Session;
use Nette\Localization\Translator;
use Nette\Http\SessionSection;

final class VisualPaginator extends Control
{

	public const SESSION_SECTION = 'visual-paginator';

	/** @persistent */
	public ?int $page = null;

	/** @persistent */
	public ?int $itemsPerPage = null;

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

	private Paginator $paginator;

	private SessionSection $sessionSection;

	private ?Translator $translator;

	public function __construct(Session $session, ?Translator $translator)
	{
		$this->paginator = new Paginator();

		$this->paginator->page = $this->page ?? 1;
		$this->paginator->itemsPerPage = $this->itemsPerPage ?? \array_keys($this->itemsPerPageList)[0];

		$this->sessionSection = $session->getSection(self::SESSION_SECTION);
		$this->translator = $translator;
	}

	public function setItemsPerPage(int $itemsPerPage): self
	{
		if (!$this->canSetItemsPerPage) {
			return $this;
		}

		if (!\array_keys($this->itemsPerPageList, $itemsPerPage)) {
			throw new InvalidArgumentException(self::class . '$itemsPerPageList has not ' . $itemsPerPage . ' key.');
		}

		/*if ($this->session !== null) {
			$this->sessionSection->offsetGet($this->getSessionReposity()) = $number;
		}*/

		$this->itemsPerPage = $itemsPerPage;
		return $this;
	}

	public function setItemCount(int $count): self
	{
		$this->paginator->itemCount = $count;
		return $this;
	}

	public function loadState(array $params): void
	{
		parent::loadState($params);

		/*if ($this->canSetItemsPerPage) {
			if (
				$this->sessionSection->offsetGet($this->getSessionReposity()) &&
				\in_array($this->sessionSection->{$this->getSessionReposity()}, array_keys(self::$itemsPerPageList), TRUE)
			) {



				$this->setItemsPerPage($this->sessionSection->{$this->getSessionReposity()});
			} else {
				unset($this->sessionSection->{$this->getSessionReposity()});
			}
		}*/

		$this->paginator->page = $this->page ?? 1;
		$this->paginator->itemsPerPage = $this->itemsPerPage ?? \array_keys($this->itemsPerPageList)[0];
		$this->page = $this->paginator->page;
		$this->itemsPerPage = $this->paginator->itemsPerPage;

		$this['itemsPerPage']->setDefaults([
			'itemsPerPage' => $this->itemsPerPage,
		]);
	}

	public function render(): void
	{
		if ($this->paginator->pageCount < 2) {
			$steps = [$this->paginator->page];
		} else {
			$arr = \range(
				\max($this->paginator->firstPage, $this->paginator->page - 2),
				\min($this->paginator->lastPage, $this->paginator->page + 2)
			);

			$count = 1;
			$quotient = ($this->paginator->pageCount - 1) / $count;

			for ($i = 0; $i <= $count; $i++) {
				$arr[] = round($quotient * $i) + $this->paginator->firstPage;
			}

			\sort($arr);

			$steps = \array_values(
				\array_unique($arr)
			);
		}

		$this->template->steps = $steps;
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
			$this->handlePaginate();

			if (!$this->presenter->isAjax()) {
				$this->redirect('this');
			}
		};

		return $form;
	}

	public function handlePaginate(): void
	{
		foreach ($this->onPaginate as $event) {
			$event();
		}
	}

	/*private function getSessionRepository(): string
	{
		if ($this->itemsPerPageRepository !== null) {
			return $this->itemsPerPageRepository;
		}

		return $this->presenter->getName();
	}*/

}
