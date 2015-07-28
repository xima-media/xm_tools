<?php

namespace Xima\XmTools\Classes\ViewHelpers\Object;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use Xima\XmTools\Classes\Helper\Helper;

/**
 * Implodes array members to string, optionally calls function on members before imploding.
 *
 * @author Wolfram Eberius <woe@xima.de>
 *
 * @return string
 */
class ArrayImplodeViewHelper extends AbstractViewHelper
{
    /**
     * Basically equal to PHP implode(). If array items are array themselves a key ($functionOrKey) can be specified.
     * If array items are objects a function to retrieve a certain value for the implode can be specified ($functionOrKey).
     *
     *
     * @param $glue string
     * @param $array Array
     * @param $functionOrKey string
     */
    public function render($glue, $array, $functionOrKey = '')
    {
        $theArray = array();

        foreach ($array as $value) {
            if ($functionOrKey != '') {
                if (is_array($value)) {
                    $value = $value[$functionOrKey];
                } else {
                    $getter = 'get'.Helper::underscoreToCamelCase($functionOrKey);
                    if (method_exists($value, $getter)) {
                        $value = $value->$getter();
                    } else {
                        $string = "\$value=\$value->$functionOrKey;";
                    }
                    eval($string);
                }
            }
            $theArray [] = trim($value);
        }

        return implode($glue, $theArray);
    }
}
