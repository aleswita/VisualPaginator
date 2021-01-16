<?php
// source: C:\xampp_74\htdocs\VisualPaginator\tests\App/templates/Test.default.latte

use Latte\Runtime as LR;

final class Templatecb6995fd4c extends Latte\Runtime\Template
{

	public function main(): array
	{
		extract($this->params);
		/* line 1 */ $_tmp = $this->global->uiControl->getComponent("paginator");
		if ($_tmp instanceof Nette\Application\UI\Renderable) $_tmp->redrawControl(null, false);
		$_tmp->render();
		return get_defined_vars();
	}


	public function prepare(): void
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}

}
