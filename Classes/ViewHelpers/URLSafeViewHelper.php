<?php
class Tx_XmTools_ViewHelpers_URLSafeViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractConditionViewHelper {
 
    /**
    * @param string $string
    * @return string
    */
    public function render($string) {
        
        //always convert umlaute instead of replacing them in english
        setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
        $string = iconv("utf-8","ascii//TRANSLIT", $string);
        $safe = preg_replace('/^-+|-+$/', '', preg_replace('/[^a-zA-Z0-9]+/', '-', $string));
        
        return $safe;
    }
}
?>