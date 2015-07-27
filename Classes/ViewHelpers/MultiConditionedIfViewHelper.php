<?php

/**
 *
 * @package TYPO3
 * @subpackage Fluid
 * @author Wolfram Eberius <woe@xima.de>
 * 
 * Combines two conditions with AND or OR.
 * 
 * @return function to render corresponding fluid child.
 */
class Tx_XmTools_ViewHelpers_MultiConditionedIfViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractConditionViewHelper {
    const CONDITION_TYPE_AND = 'AND';
    const CONDITION_TYPE_OR = 'OR';

    /**
     * renders <f:then> child if $condition and/or $or is true, otherwise renders <f:else> child.
     *
     * @param boolean $condition1 View helper condition
     * @param boolean $condition2 View helper condition
     * @param boolean $conditionType string
     * @return boolean the rendered string
     */
    public function render($condition1, $condition2, $conditionType = '') {

        switch ($conditionType) {
            case Tx_XmTools_ViewHelpers_MultiConditionedIfViewHelper::CONDITION_TYPE_OR :
                {
                    $condition = $condition1 || $condition2;
                    break;
                }
            default :
                {
                    $condition = $condition1 && $condition2;
                    break;
                }
        }
        
        if ($condition) {
            return $this->renderThenChild ();
        }
        else {
            return $this->renderElseChild ();
        }
    }
}