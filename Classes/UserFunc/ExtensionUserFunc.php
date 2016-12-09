<?php

namespace Xima\XmTools\UserFunc;

use Xima\XmTools\Extensionmanager\ExtensionUtility;


/**
 * Class ExtensionUserFunc
 *
 * @package Xima\XmTools\UserFunc
 */
class ExtensionUserFunc
{

    /**
     * Returns the version of the given extension
     *
     * @param  string          Empty string (no content to process)
     * @param  array           TypoScript configuration
     * @return string          Version
     *
     * Example:
     * lib.version = USER
     * lib.version {
     *      userFunc = Xima\XmTools\UserFunc\ExtensionUserFunc->getExtensionVersion
     *      # extensionKey [mandatory]
     *      extensionKey = xm_tools
     *      # Overrides default label [optional]
     *      label = Version:&nbsp;
     * }
     *
     */
    public function getExtensionVersion($content, $conf)
    {
        $version = null;
        $label = (array_key_exists('label', $conf)) ? $conf['label'] : 'Version:&nbsp;';

        if (array_key_exists('extensionKey', (array)$conf)) {
            $version = ExtensionUtility::getExtensionVersion($conf['extensionKey']);
        }

        return (null == $version) ? $label . 'No version found.' : $label . $version;
    }

}
