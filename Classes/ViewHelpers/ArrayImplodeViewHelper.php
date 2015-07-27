<?php

/**
 *
 * @package TYPO3
 * @subpackage Fluid
 * @author Wolfram Eberius <woe@xima.de>
 * 
 * Implodes array members to string, optionally calls function on members before imploding.
 * 
 * @return string
 */
class Tx_XmTools_ViewHelpers_ArrayImplodeViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

    /**
     *
     * @param $glue string
     * @param $array Array            
     * @param $functionOrKey string            
     */
    public function render($glue, $array, $functionOrKey = '') {

        $theArray = array ();
        
        foreach ( $array as $value ) {
            if ($functionOrKey != '') {
                if (is_array ($value)) {
                    $value = $value[$functionOrKey];
                }
                else {
                    $string = "\$value=\$value->$functionOrKey;";
                    eval ($string);
                }
            }
            $theArray [] = trim ($value);
        }
        
        return implode ($glue, $theArray);
    }
}

?> 