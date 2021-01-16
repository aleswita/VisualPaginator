<?php declare(strict_types = 1);

namespace AlesWita\Components;

use Nette\Utils\Paginator;

final class Template
{

	public array $steps;

	public bool $itemsPerPage;

	public Paginator $paginator;

	public bool $ajax;

}
