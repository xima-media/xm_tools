<?php

namespace Xima\XmTools\Classes\Typo3\Extension;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use Xima\XmTools\Classes\Typo3\Model\Extension;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExtensionHelper
{
    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     * @inject
     */
    protected $configurationManagerInterface;

    protected $extensionName;

    /**
     * @param string $extensionName
     *
     * @return \Xima\XmTools\Classes\Typo3\Model\Extension
     */
    public function getExtension($extensionName = null)
    {
        $extension = null;
        $this->extensionName = $extensionName;

        if (is_null($this->extensionName)) {
            switch (TYPO3_MODE) {
                case 'FE':
                    //get the extensions name that causes the call - do this only for frontend plugins that call directly, e.g. ajax
                    $configuration = $this->configurationManagerInterface->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
                    $this->extensionName = $configuration ['extensionName'];
                    break;
            }
        }

        if ($this->extensionName) {
            $extensionKey = self::getExtensionKeyByExtensionName($this->extensionName);
            if (in_array($extensionKey, ExtensionManagementUtility::getLoadedExtensionListArray())) {
                $extension = new Extension();

                /* @var $extension \Xima\XmTools\Classes\Typo3\Model\Extension */
                $extension->setName($this->extensionName);
                $extension->setKey($extensionKey);
                $extension->setRelPath(ExtensionManagementUtility::siteRelPath($extension->getKey()));
                $extension->setExtPath(ExtensionManagementUtility::extPath($extension->getKey()));
                $extension->setSettings($this->getSettings());

                $extensionConfiguration = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$extensionKey];
                if (is_string($extensionConfiguration)) {
                    $configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$extensionKey]);
                    $extension->setConfiguration($configuration);
                }

            }
        }

        return $extension;
    }

    /**
     * Converts a TYPO3 extension name to the extension key.
     *
     * @param string $extensionName
     *
     * @return string
     */
    public static function getExtensionKeyByExtensionName($extensionName)
    {
        $extensionKey = '';

        for ($i = 0; $i < strlen($extensionName); $i++) {
            $chr = mb_substr($extensionName, $i, 1, 'UTF-8');

            if ((mb_strtolower($chr, 'UTF-8') != $chr) && $i > 0) {
                $extensionKey .= '_';
            }

            $extensionKey .= $chr;
        }

        $extensionKey = strtolower($extensionKey);

        return $extensionKey;
    }

    public static function getTyposcriptSetup()
    {
        $tsSetup = array();

        $manager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager');
        /* @var $manager \TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager */
        $service = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Service\TypoScriptService');
        /* @var $service \TYPO3\CMS\Extbase\Service\TypoScriptService */

        if (!is_null($manager->getTypoScriptSetup())) {
            $tsSetup = $service->convertTypoScriptArrayToPlainArray($manager->getTypoScriptSetup());
        }

        return $tsSetup;
    }

    /**
     * Gets the extension settings.
     *
     * @return array
     */
    protected function getSettings()
    {
        $settings = array();

        // TYPO3 mode dependent retrieval of extension's settings
        switch (TYPO3_MODE) {
            case 'FE':
                $settings = $this->getFESettings();
                break;
            case 'BE':
                $settings = $this->getBESettings();
                break;
        }

        return $settings;
    }

    /**
     * @return array
     */
    protected function getFESettings()
    {
        $settings = array();
        $tsSetup = self::getTyposcriptSetup();

        $extensionKey = self::getExtensionKeyByExtensionName($this->extensionName);
        if (isset($tsSetup['config'][$extensionKey])) {
            $settings = $tsSetup['config'][$extensionKey];
        }

        $extensionSignature = 'tx_' . strtolower($this->extensionName);
        if (isset($tsSetup['plugin'][$extensionSignature])) {
            $settings = array_merge($settings, $tsSetup['plugin'][$extensionSignature]);
        }

        return $settings;
    }

    /**
     * Gets the plugin's settings.
     *
     * @return array
     */
    public static function getPluginSettings($extensionName, $pluginName)
    {
        $settings = array();
        $tsSetup = self::getTyposcriptSetup();

        $pluginSignature = 'tx_' . strtolower($extensionName . '_' . $pluginName);
        if (isset($tsSetup['plugin'][$pluginSignature])) {
            $settings = $tsSetup['plugin'][$pluginSignature];
        }

        return $settings;
    }

    /**
     * Gets the extension's backend settings.
     *
     * @return array
     */
    protected function getBESettings()
    {
        $settings = array();

        // load configurations before accessing ypoScriptSetupCache!
        $this->configurationManagerInterface->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);

        $manager = GeneralUtility::makeInstance("TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager");
        $service = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Service\TypoScriptService');

        // access the typoscript cache
        $arrOfObj = (array)$manager;

        $tsKey = "\0*\0typoScriptSetupCache";
        if (is_array($arrOfObj) && isset($arrOfObj[$tsKey])) {
            $config = $arrOfObj[$tsKey];
            $setup = $service->convertTypoScriptArrayToPlainArray(array_pop($config));
            $txExtensionName = 'tx_' . \strtolower($this->extensionName);
            if (isset($setup['plugin'][$txExtensionName])) {
                $settings = $setup['plugin'][$txExtensionName];
            }
        }

        return $settings;
    }


}
