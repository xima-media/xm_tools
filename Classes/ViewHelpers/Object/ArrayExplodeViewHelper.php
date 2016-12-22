<?php

namespace Xima\XmTools\ViewHelpers\Object;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Explodes a string by a given delimiter.
 *
 * = Example =
 *
 * {namespace xmTools = Xima\XmTools\Classes\ViewHelpers}
 * <f:for each="{xmTools:object.ArrayExplode(delimiter:',',string:someString)}" as="item">
 *  {item}
 * </f:for>
 *
 * @todo: Move example to external file (ArrayExplodeViewHelper.md) and include as annotation 'example'
 *
 * @author Wolfram Eberius <woe@xima.de>
 * @return array
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
