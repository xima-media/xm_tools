<?php

namespace Xima\XmTools\ViewHelpers\Object;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Implodes array members to string, optionally calls function on members before imploding.
 *
 * = Example =
 *
 * {namespace xmTools = Xima\XmTools\ViewHelpers}
 * <xmTools:object.ArrayImplode glue=", " array="{someArray}", functionOrKey="string|int">
 *
 * @todo Move example to external file (ArrayImplodeViewHelper.md) and include as annotation 'example'
 *
 * @author Wolfram Eberius <woe@xima.de>, Steve Lenz <steve.lenz@xima.de>
 * @return string
 */
class ArrayImplodeViewHelper extends AbstractViewHelper
{

    /**
     * Basically equal to PHP implode(). If array items are array themselves a key ($functionOrKey) can be specified.
     * If array items are objects a function to retrieve a certain value for the implode can be specified ($functionOrKey).
     *
     * @param $glue string
     * @param $array array
     * @param string $functionOrKey string
     * @return string
     */
    public function render($glue, $array, $functionOrKey = '')
    {
        $theArray = array();

        foreach ($array as $value) {
            if ($functionOrKey != '') {
                if (is_array($value)) {
                    $value = $value[$functionOrKey];
                } else {
                    $getter = 'get' . GeneralUtility::underscoredToUpperCamelCase($functionOrKey);
                    if (method_exists($value, $getter)) {
                        $value = $value->$getter();
                    } else {
                        $value = $value->{$functionOrKey};
                    }
                }
            }
            $theArray[] = trim($value);
        }

        return implode($glue, $theArray);
    }

}
