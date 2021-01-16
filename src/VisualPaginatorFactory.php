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
		return new VisualPaginator(
			$this->session,
			$this->translator
		);
	}

}
