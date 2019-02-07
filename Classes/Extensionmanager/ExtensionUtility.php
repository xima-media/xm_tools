<?php

namespace Xima\XmTools\Extensionmanager;

use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extensionmanager\Utility\ListUtility;


/**
 * Class ExtensionUtility
 *
 * @author Steve Lenz <steve.lenz@xima.de>, Sebastian Gierth <sebastian.gierth@xima.de>
 * @package Xima\XmTools\Extensionmanager
 */
class ExtensionUtility
{
    /**
     * @var array
     */
    protected static $configurations = [];

    /**
     * Returns the version of the given extension
     *
     * @param $extensionKey
     * @return mixed|null
     * @throws \TYPO3\CMS\Core\Package\Exception\UnknownPackageException
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
     * @param string $type Options:module|plugin
     * @return array
     */
    public static function getTypoScriptPluginSetup($extKey, $pluginName = '', $type = 'plugin')
    {
        $pluginKey = strtolower(str_replace('_', '', $extKey));
        $pluginKey .= ($pluginName) ? '_' . strtolower($pluginName) : '';
        $pluginKey = 'tx_' . $pluginKey . '.';

        /** @var ObjectManager $om */
        $om = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var ConfigurationManagerInterface $confMngr */
        $confMngr = $om->get(ConfigurationManagerInterface::class);
        $tsSetup = $confMngr->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);

        if (is_array($tsSetup) && array_key_exists($pluginKey, $tsSetup[$type . '.'])) {
            return static::removeDots((array)$tsSetup[$type . '.'][$pluginKey]);
        } else {
            return [];
        }
    }

    /**
     * Returns configuration for given extension, optionally by the name of the specific configuration.
     *
     * @param string $extKey
     * @param string $confName
     * @return mixed|null
     */
    public static function getConfiguration($extKey, $confName = '')
    {
        $config = static::loadConfiguration($extKey, $confName);
        if ($config !== null){
            return $config;
        }

        if ( ! isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$extKey])){
            return null;
        }

        $config = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$extKey]);
        if (is_array($config)){
            $config = ArrayUtility::flatten($config);
        }
        static::$configurations[$extKey] = $config;

        return static::loadConfiguration($extKey, $confName);
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

    /**
     * Load configuration from internal storage by $extKey and $confName.
     *
     * @param string $extKey
     * @param string $confName
     * @return mixed|null Returns payload otherwise null if configuration was not found.
     */
    private static function loadConfiguration($extKey, $confName = '')
    {
        if (array_key_exists($extKey, static::$configurations)){
            if (array_key_exists($confName, static::$configurations[$extKey])){
                return static::$configurations[$extKey][$confName];
            }
            else {
                return static::$configurations[$extKey];
            }
        }
        else {
            return null;
        }
    }

}
