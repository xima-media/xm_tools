<?php

namespace Xima\XmTools\ViewHelpers\Object;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Returns an array element by key
 *
 * = Example =
 *
 * {namespace xmTools = Xima\XmTools\ViewHelpers}
 * <xmTools:object.arrayElement array="{array}" key="{key}" variableName="myValue">
 *      {myValue}
 * </xmTools:object.arrayElement>
 *
 * @todo: Move example to external file (ArrayElementViewHelper.md) and include as annotation 'example'
 *
 * @author Steve Lennz <steve.lenz@xima.de>
 * @return array
 */
class ArrayElementViewHelper extends AbstractViewHelper
{

    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('array', 'array', 'Array to search in', true);
        $this->registerArgument('key', 'string', 'Key to search for', true);
        $this->registerArgument('variableName', 'string', 'Variablename to set', false, 'element');
    }

    /**
     * @return string content
     */
    public function render()
    {
        $array = $this->arguments['array'];
        $key = $this->arguments['key'];
        $variableName = $this->arguments['variableName'];

        if (!is_array($array) || empty($array) || !array_key_exists($key, $array)) {
            $value = null;
        } else {
            $value = $array[$key];
        }

        $this->templateVariableContainer->add($variableName, $value);
        $content = $this->renderChildren();
        $this->templateVariableContainer->remove($variableName);

        return $content;
    }

}
