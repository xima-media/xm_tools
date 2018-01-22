<?php

namespace Xima\XmTools\ViewHelpers\Object;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Explodes a string by a given delimiter.
 *
 * = Example =
 *
 * {namespace xmTools = Xima\XmTools\ViewHelpers}
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
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('delimiter', 'string', 'Specifies where to break the String', true);
        $this->registerArgument('string', 'string', 'The String to split', true);
    }

    /**
     * Basically equal to PHP explode().
     *
     * @return array
     */
    public function render()
    {
        $delimiter = $this->arguments['delimiter'];
        $string = $this->arguments['string'];

        return explode($delimiter, $string);
    }

}
