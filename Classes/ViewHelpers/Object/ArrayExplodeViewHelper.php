<?php
namespace Xima\XmTools\Classes\ViewHelpers\Object;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Explodes a string by a given delimiter.
 *
 * @package xm_tools
 * @author Wolfram Eberius <woe@xima.de>
 *
 * @return string
 */
class ArrayExplodeViewHelper extends AbstractViewHelper
{

    /**
     * Basically equal to PHP explode(). 
     *
     * @param $delimiter string
     * @param $string Array
     * 
     * @return array
     */
    public function render($delimiter, $string)
    {
        return explode($delimiter, $string);
    }
}
