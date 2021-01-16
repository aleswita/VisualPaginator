<?php declare(strict_types = 1);

namespace AlesWita\Components;

use Nette\Http\Session;
use Nette\Localization\Translator;

final class VisualPaginatorFactory
{

	private Session $session;

	private ?Translator $translator;

	public function __construct(Session $session, ?Translator $translator)
	{
		$this->session = $session;
		$this->translator = $translator;
	}

	public function create(): VisualPaginator
	{
		$paginator = new VisualPaginator($this->session);

		if ($this->translator !== null) {
			$paginator->messages = array_map(
				function (string $message): string {
					return $this->translator->translate($message);
				},
				$paginator->messages
			);
		}

		return $paginator;
	}

}
