<?php

/**
 *
 * @package TYPO3
 * @subpackage Fluid
 * @author Wolfram Eberius <woe@xima.de>
 * 
 * Applies different php-known array check operations.
 * 
 * @return boolean
 */
class Tx_XmTools_ViewHelpers_ArrayCheckViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {
    const CONDITION_IN = 'IN';
    const CONDITION_NOT_IN = 'NOT_IN';
    const CONDITION_NOT_FIRST = 'NOT_FIRST';
    const CONDITION_NOT_LAST = 'NOT_LAST';
    const CONDITION_EMPTY = 'EMPTY';
    const CONDITION_NOT_EMPTY = 'NOT_EMPTY';
    const CONDITION_IS_ARRAY = 'IS_ARRAY';

    /**
     *
     * @param $array Array            
     * @param $needle Object            
     * @param $check string            
     */
    public function render($array, $needle = '', $check = '') {
        switch ($check) {
            case Tx_XmTools_ViewHelpers_ArrayCheckViewHelper::CONDITION_IN :
                {
                    return in_array ( $needle, $array );
                    break;
                }
            case Tx_XmTools_ViewHelpers_ArrayCheckViewHelper::CONDITION_NOT_IN :
                {
                    return !in_array ( $needle, $array );
                    break;
                }
            case Tx_XmTools_ViewHelpers_ArrayCheckViewHelper::CONDITION_NOT_FIRST :
                {
                    return (array_shift ( $array ) != $needle);
                    break;
                }
            case Tx_XmTools_ViewHelpers_ArrayCheckViewHelper::CONDITION_NOT_LAST :
                {
                    return (array_pop ( $array ) != $needle);
                    break;
                }
            case Tx_XmTools_ViewHelpers_ArrayCheckViewHelper::CONDITION_EMPTY :
                {
                    return empty ( $array );
                    break;
                }
            case Tx_XmTools_ViewHelpers_ArrayCheckViewHelper::CONDITION_NOT_EMPTY :
                {
                    return ! empty ( $array );
                    break;
                }
            case Tx_XmTools_ViewHelpers_ArrayCheckViewHelper::CONDITION_IS_ARRAY :
                {
                    return is_array( $array );
                    break;
                }
            default :
                
                return false;
        }
    }
}

?> 