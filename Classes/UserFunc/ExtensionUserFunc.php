<?php

namespace Xima\XmTools\UserFunc;

use TYPO3\CMS\Core\Package\Exception\UnknownPackageException;
use Xima\XmTools\Extensionmanager\ExtensionUtility;

/**
 * Class ExtensionUserFunc
 *
 * @author Steve Lenz <steve.lenz@xima.de>
 */
class ExtensionUserFunc
{
    /**
     * Returns the version of the given extension
     *
     * @param string $content Empty string (no content to process)
     * @param array $conf TypoScript configuration
     * @return string          Version
     * Example:
     * lib.version = USER
     * lib.version {
     *      userFunc = Xima\XmTools\UserFunc\ExtensionUserFunc->getExtensionVersion
     *      # extensionKey [mandatory]
     *      extensionKey = xm_tools
     *      # Overrides default label [optional]
     *      label = Version:&nbsp;
     * }
     * @throws UnknownPackageException
     */
    public function getExtensionVersion(string $content, array $conf): string
    {
        $version = null;
        $label = (array_key_exists('label', $conf)) ? $conf['label'] : 'Version:&nbsp;';

        if (array_key_exists('extensionKey', $conf)) {
            $version = ExtensionUtility::getExtensionVersion($conf['extensionKey']);
        }

        return (null === $version) ? $label . 'No version found.' : $label . $version;
    }
}
