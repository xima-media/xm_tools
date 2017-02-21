<?php

namespace Xima\XmTools\Extensionmanager;

use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extensionmanager\Utility\ListUtility;


/**
 * Class ExtensionUtility
 *
 * @author Steve Lenz <steve.lenz@xima.de>
 * @package Xima\XmTools\Extensionmanager
 */
class ExtensionUtility
{

    /**
     * Returns the version of the given extension
     *
     * @param $extensionKey
     * @return mixed|null
     */
    public static function getExtensionVersion($extensionKey)
    {
        $version = null;
        /** @var ObjectManager $om */
        $om = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var ListUtility $listUtility */
        $listUtility = $om->get(ListUtility::class);

        $packages = $listUtility->getAvailableExtensions();

        if (array_key_exists($extensionKey, $packages)) {
            /** @var Package $ext */
            $package = $listUtility->getExtension($extensionKey);

            if ($package instanceof Package) {
                $version = $package->getValueFromComposerManifest('version');
            }
        }

        return $version;
    }

    /**
     * Returns TypoScript plugin setup for given extension
     *
     * @param string $extKey
     * @param string $pluginName
     * @return array
     */
    public static function getTypoScriptPluginSetup($extKey, $pluginName = '')
    {
        $pluginKey = strtolower(str_replace('_', '', $extKey));
        $pluginKey .= ($pluginName) ? '_' . strtolower($pluginName) : '';
        $pluginKey = 'tx_' . $pluginKey . '.';

        /** @var ObjectManager $om */
        $om = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var ConfigurationManagerInterface $confMngr */
        $confMngr = $om->get(ConfigurationManagerInterface::class);
        $tsSetup = $confMngr->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);

        if (array_key_exists($pluginKey, $tsSetup['plugin.'])) {
            return static::removeDots((array)$tsSetup['plugin.'][$pluginKey]);
        } else {
            return [];
        }
    }

    /**
     * Removes dots in array keys
     *
     * @param array $array
     * @return array
     */
    private static function removeDots(array $array)
    {
        $conf = [];

        foreach ($array as $key => $value) {
            $conf[preg_replace('/\.$/', '', $key)] = is_array($value) ? static::removeDots($value) : $value;
        }

        return $conf;
    }

}
