<?php

namespace Xima\XmTools\Classes\ViewHelpers\Object;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Replace all occurrences of the search string with the replacement string.
 *
 * @see http://php.net/manual/en/function.str-replace.php
 * @author Sebastian Gierth <sgi@xima.de>
 */
class StrReplaceViewHelper extends AbstractViewHelper
{
    /**
     * Replace the string with str_replace().
     *
     * @param string $search The value being searched for, otherwise known as the needle. An array may be used to designate multiple needles.
     * @param string $replace The replacement value that replaces found search values. An array may be used to designate multiple replacements.
     * @return string The replaced string
     */
    public function render($search, $replace)
    {
        return str_replace($search, $replace, $this->renderChildren());
    }
}
