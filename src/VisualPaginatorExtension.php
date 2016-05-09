<?php

/**
 * This file is part of the AlesWita\VisualPaginator
 * Copyright (c) 2015 Ales Wita (aleswita@gmail.com)
 */

namespace AlesWita\Components;

use Nette;


/**
 * @author Aleš Wita
 */
class VisualPaginatorExtension extends Nette\DI\CompilerExtension
{
	/** @var array */
	public $defaults = [
		"session" => NULL,
		"translator" => NULL,
		"template" => VisualPaginator::TEMPLATE_NORMAL,
		"texts" => NULL,
	];

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$container = $this->getContainerBuilder();

		$vp = $container->addDefinition($this->prefix("visualpaginator"))
			->setClass("AlesWita\\Components\\VisualPaginator");

		if ($config["session"] !== NULL) {
			$vp->addSetup('$service->setSession(?)', [$config["session"]]);
		}
		if ($config["translator"] !== NULL) {
			$vp->addSetup('$service->setTranslator(?)', [$config["translator"]]);
		}
		if ($config["template"] !== VisualPaginator::TEMPLATE_NORMAL) {
			$vp->addSetup('$service->setPaginatorTemplate(?)', [$config["template"]]);
		}
		if ($config["texts"] !== NULL) {
			foreach ($config["texts"] as $arr) {
				$vp->addSetup('$service->setText(?,?)', [$arr[0], $arr[1]]);
			}
		}
	}
}
