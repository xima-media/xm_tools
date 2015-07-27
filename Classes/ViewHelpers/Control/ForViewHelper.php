<?php
namespace Xima\XmTools\Classes\ViewHelpers\Control;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View helper with for loop like for int i = 0; i++; ...
 *
 * Define this in views as follows:
 * {namespace mk=Tx_MyExt_ViewHelpers}
 *
 * <mk:for min="0" max="{value}" variable="i">{i}</mk:for>
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * (c) 2012 Maximilian Kalus
 */
class ForViewHelper extends AbstractViewHelper
{
    /**
     * Returns selected="selected", if $value is contained in $inData
     * @param int    $max
     * @param int    $min
     * @param int    $step
     * @param string $variableName variable name
     */
    public function render($max, $min = 0, $step = 1, $variableName = 'i')
    {
        $content = '';

        for ($i = $min; $i <= $max; $i += $step) {
            $this->templateVariableContainer->add($variableName, $i);
            $content .= $this->renderChildren();
            $this->templateVariableContainer->remove($variableName);
        }

        return $content;
    }
}
