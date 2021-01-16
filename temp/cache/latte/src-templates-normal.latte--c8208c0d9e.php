<?php
// source: C:\xampp_74\htdocs\VisualPaginator\src/templates/normal.latte

use Latte\Runtime as LR;

final class Templatec8208c0d9e extends Latte\Runtime\Template
{

	public function main(): array
	{
		extract($this->params);
		echo "\n";
		if ($paginator->isFirst()) {
			echo '  <span>&laquo;</span>
';
		}
		else {
			echo '  <a href="';
			echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("paginate!", ['page' => $paginator->page - 1]));
			echo '"';
			echo ($ʟ_tmp = array_filter([$ajax ? 'ajax' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "";
			echo '>&laquo;</a>
';
		}
		echo "\n";
		$iterations = 0;
		foreach ($iterator = $ʟ_it = new LR\CachingIterator($steps, $ʟ_it ?? null) as $step) {
			echo '  <strong>
    <a href="';
			echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("paginate!", ['page' => $step]));
			echo '"';
			echo ($ʟ_tmp = array_filter([$ajax ? 'ajax' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "";
			echo '>';
			echo LR\Filters::escapeHtmlText($step) /* line 11 */;
			echo '</a>
  </strong>
';
			if ($iterator->nextValue > $step + 1) {
				echo '    <span>&hellip;</span>
';
			}
			$iterations++;
		}
		$iterator = $ʟ_it = $ʟ_it->getParent();
		echo "\n";
		if ($paginator->isLast()) {
			echo '  <span>&raquo;</span>
';
		}
		else {
			echo '  <a href="';
			echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("paginate!", ['page' => $paginator->page + 1]));
			echo '"';
			echo ($ʟ_tmp = array_filter([$ajax ? 'ajax' : null])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "";
			echo '>&raquo;</a>
';
		}
		echo "\n";
		if ($itemsPerPage) {
			$formClass = $ajax ? 'ajax' : null;
			echo '  ';
			/* line 26 */
			echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["itemsPerPage"], ['class' => $formClass]);
			echo '
    <table>
      <tr>
        <td>
          ';
			if ($_label = end($this->global->formsStack)["itemsPerPage"]->getLabel()) echo $_label;
			echo '
        </td>
        <td>
          ';
			echo end($this->global->formsStack)["itemsPerPage"]->getControl() /* line 33 */;
			echo '
        </td>
        <td>
          ';
			echo end($this->global->formsStack)["send"]->getControl() /* line 36 */;
			echo '
        </td>
      </tr>
    </table>
  ';
			echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack));
			echo "\n";
		}
		return get_defined_vars();
	}


	public function prepare(): void
	{
		extract($this->params);
		if (!$this->getReferringTemplate() || $this->getReferenceType() === "extends") {
			foreach (array_intersect_key(['step' => '9'], $this->params) as $ʟ_v => $ʟ_l) {
				trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
			}
		}
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}

}
