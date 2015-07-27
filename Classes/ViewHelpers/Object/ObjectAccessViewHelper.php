<?php
namespace Xima\XmTools\Classes\ViewHelpers\Object;

/**
 * Allows accessing an array's value by its key or object's property or method by its name
 * in order to achieve a "dynamic" path in Fluid, kind of
 * {fooarray.{dynamic}}, which is not possible yet, can be replaced
 * with {my:objectAccess(haystack: fooarray, needle: key)}
 *
 * @package xm_tools
 * @author Wolfram Eberius <woe@xima.de>
 *
 * @return mixed|false
 */
class ObjectAccessViewHelper extends AbstractViewHelper
{

    /**
     * @param  mixed  $haystack
     * @param  string $needle
     * @param  string $methodPrefix
     * @return mixed
     */
    public function render($haystack, $needle, $methodPrefix = null)
    {
        if (is_array($haystack)) {
            if (array_key_exists($needle, $haystack)) {
                return $haystack[$needle];
            } else {
                return;
            }
        } elseif (is_object($haystack)) {
            if (isset($haystack->$needle) || property_exists($haystack, $needle)) {
                return $haystack->$needle;
            } elseif (method_exists($haystack, $methodPrefix.$needle)) {
                return $haystack->{$methodPrefix.$needle}();
            }
        } else {
            return;
        }
    }
}
