<?php
namespace Xima\XmTools\ViewHelpers\Object;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

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
     * @param array $array to search in
     * @param string $key to search for
     * @param string $variableName variable name to set
     * @return string content
     */
    public function render($array, $key, $variableName = 'element')
    {
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
