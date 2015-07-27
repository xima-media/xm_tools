<?php
namespace Xima\XmTools\Classes\ViewHelpers\Control;

/**
 * Combines two conditions with AND or OR.
 *
 * @package xm_tools
 * @author Wolfram Eberius <woe@xima.de>
 * @deprecated
 *
 * @return function to render corresponding fluid child.
 */
class MultiConditionedIfViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper
{

    const CONDITION_TYPE_AND = 'AND';
    const CONDITION_TYPE_OR = 'OR';

    /**
     * renders <f:then> child if $condition and/or $or is true, otherwise renders <f:else> child.
     *
     * @param  boolean $condition1    View helper condition
     * @param  boolean $condition2    View helper condition
     * @param  string  $conditionType
     * @return string  the rendered string
     */
    public function render($condition1, $condition2, $conditionType = '')
    {
        $conditionType = strtoupper($conditionType);
        switch ($conditionType) {
            case self::CONDITION_TYPE_OR:
                    $condition = $condition1 || $condition2;
                    break;
            case self::CONDITION_TYPE_AND:
            default :
                    $condition = $condition1 && $condition2;
                    break;
        }

        if ($condition) {
            return $this->renderThenChild();
        } else {
            return $this->renderElseChild();
        }
    }
}
