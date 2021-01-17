<?php declare(strict_types = 1);

namespace AlesWita\VisualPaginator;

use Nette\Utils\Paginator;

final class Helpers
{

	/**
	 * @param array<callable> $events
	 */
	public static function callEvents(array $events): void
	{
		foreach ($events as $event) {
			$event();
		}
	}

	/**
	 * @return array<int>
	 */
	public static function createSteps(Paginator $paginator): array
	{
		if ($paginator->getPageCount() < 2) {
			$steps = [$paginator->getPage()];
		} else {
			$arr = range(
				max($paginator->getFirstPage(), $paginator->getPage() - 2),
				min((int) $paginator->getLastPage(), $paginator->getPage() + 2)
			);

			$count = 1;
			$quotient = ($paginator->getPageCount() - 1) / $count;

			for ($i = 0; $i <= $count; $i++) {
				$arr[] = round($quotient * $i) + $paginator->getFirstPage();
			}

			sort($arr);

			$steps = array_values(
				array_unique($arr)
			);
		}

		return $steps;
	}

}
