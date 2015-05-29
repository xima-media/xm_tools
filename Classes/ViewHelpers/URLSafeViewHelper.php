<?php

namespace Xima\XmTools\Classes\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\ViewHelper;

/**
 * Make a string URL safe.
 *
 * @author Wolfram Eberius <woe@xima.de>
 *
 * @return string
 */
class URLSafeViewHelper extends ViewHelper
{
    /**
     * @param string $string
     *
     * @return string
     */
    public function render($string)
    {

        //always convert umlaute instead of replacing them in english
        setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
        $string = iconv('utf-8', 'ascii//TRANSLIT', $string);
        $safe = preg_replace('/^-+|-+$/', '', preg_replace('/[^a-zA-Z0-9]+/', '-', $string));

        return $safe;
    }
}
