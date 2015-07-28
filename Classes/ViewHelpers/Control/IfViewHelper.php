<?php

namespace Xima\XmTools\Classes\ViewHelpers\Control;

/**
 * Combines two conditions with AND or OR.
 *
 * @author Wolfram Eberius <woe@xima.de>
 *
 * @return function to render corresponding fluid child.
 */
class IfViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper
{
    const CONDITION_TYPE_AND = 'AND';
    const CONDITION_TYPE_OR = 'OR';

    /**
     * renders <f:then> child if $conditions and/or combined is true, otherwise renders <f:else> child.
     *
     * @param array  $conditions    View helper conditions
     * @param string $conditionType
     *
     * @return string the rendered string
     */
    public function render($conditions, $conditionType = 'OR')
    {
        $result = false;
        foreach ($conditions as $condition) {
            switch ($conditionType) {
                case self::CONDITION_TYPE_AND:
                    $result = $result && $condition;
                    break;
                case self::CONDITION_TYPE_OR:
                    $result = $result || $condition;
                    break;

            }
        }

        if ($result) {
            return $this->renderThenChild();
        } else {
            return $this->renderElseChild();
        }
    }
}
