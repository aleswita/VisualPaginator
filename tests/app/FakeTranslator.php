<?php

/**
 * This file is part of the AlesWita\Components\VisualPaginator
 * Copyright (c) 2015 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\VisualPaginator\Tests\App;

use Nette;


final class FakeTranslator implements Nette\Localization\ITranslator
{
	/**
	 * @param string
	 * @param int
	 */
	public function translate($message, $count = NULL): ?string {
		return NULL;
	}
}
