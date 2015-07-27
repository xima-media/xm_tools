<?php
namespace Xima\XmTools\Classes\ViewHelpers\Object;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Offers different php-known array check operations.
 *
 * @package xm_tools
 * @author Wolfram Eberius <woe@xima.de>
 *
 * @return boolean
 */
class ArrayCheckViewHelper extends AbstractViewHelper
{

    const CONDITION_IN = 'IN';
    const CONDITION_NOT_IN = 'NOT_IN';
    const CONDITION_NOT_FIRST = 'NOT_FIRST';
    const CONDITION_NOT_LAST = 'NOT_LAST';
    const CONDITION_EMPTY = 'EMPTY';
    const CONDITION_NOT_EMPTY = 'NOT_EMPTY';
    const CONDITION_IS_ARRAY = 'IS_ARRAY';
    const CONDITION_IN_KEYS = 'IN_KEYS';
    const CONDITION_NOT_IN_KEYS = 'NOT_IN_KEYS';

    /**
     *
     * @param $array Array
     * @param $needle Object
     * @param $check string
     */
    public function render($array, $needle = '', $check = '')
    {
        switch ($check) {
            case ArrayCheckViewHelper::CONDITION_IN :
                {
                    return in_array($needle, $array);
                    break;
                }
            case ArrayCheckViewHelper::CONDITION_NOT_IN :
                {
                    return !in_array($needle, $array);
                    break;
                }
            case ArrayCheckViewHelper::CONDITION_NOT_FIRST :
                {
                    return (array_shift($array) != $needle);
                    break;
                }
            case ArrayCheckViewHelper::CONDITION_NOT_LAST :
                {
                    return (array_pop($array) != $needle);
                    break;
                }
            case ArrayCheckViewHelper::CONDITION_EMPTY :
                {
                    return empty($array);
                    break;
                }
            case ArrayCheckViewHelper::CONDITION_NOT_EMPTY :
                {
                    return ! empty($array);
                    break;
                }
            case ArrayCheckViewHelper::CONDITION_IS_ARRAY :
                {
                    return is_array($array);
                    break;
                }
            case ArrayCheckViewHelper::CONDITION_IN_KEYS :
                {
                    return in_array($needle, array_keys($array));
                    break;
                }
            case ArrayCheckViewHelper::CONDITION_NOT_IN_KEYS :
                {
                    return !in_array($needle, array_keys($array));
                    break;
                }
            default :

                return false;
        }
    }
}
