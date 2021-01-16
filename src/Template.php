<?php declare(strict_types = 1);

namespace AlesWita\VisualPaginator;

use Nette\Utils\Paginator;

final class Template
{

	/**
	 * @var array<int>
	 */
	public array $steps;

	public bool $itemsPerPage;

	public Paginator $paginator;

	public bool $ajax;

}
