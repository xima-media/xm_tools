<?php
use TYPO3\Flow\Annotations as Flow;
 
/**
 * Allows accessing an array's value by its key or object's property or method by its name
 * in order to achieve a "dynamic" path in Fluid, kind of
 * {fooarray.{dynamic}}, which is not possible yet, can be replaced
 * with {my:objectAccess(haystack: fooarray, needle: key)}
 */
class Tx_XmTools_ViewHelpers_ObjectAccessViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractConditionViewHelper {
 
    /**
    * @param mixed $haystack
    * @param string $needle
    * @param string $methodPrefix
    * @return mixed
    */
    public function render($haystack, $needle, $methodPrefix = null) {
        if (is_array($haystack)) {
            if (array_key_exists($needle, $haystack)) {
                return $haystack[$needle];
            } else {
                return null;
            }
        } elseif (is_object($haystack)) {
            if (isset($haystack->$needle) || property_exists($haystack, $needle)) {
                return $haystack->$needle;
            } elseif (method_exists($haystack, $methodPrefix . $needle)) {
                return $haystack->{$methodPrefix . $needle}();
            }
        } else {
            return null;
        }
    }
}
?>