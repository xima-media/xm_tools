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

                $settings = $this->getSettings();
                $extension->setSettings($settings);

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

    /**
     * Gets plugin settings - when there is a plugin named like the extension.
     *
     * @return array
     */
    public function getSettings()
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
     * Gets the current plugin's settings.
     *
     * @return array
     *
     * @fixme: this way is very slow.
     */
    private function getFESettings()
    {
        $settings = array();

        // load configurations
        $manager = GeneralUtility::makeInstance("TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager");
        /* @var $manager \TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager */
        $service = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Service\TypoScriptService');

        $tsSetup = $manager->getTypoScriptSetup();
        if (is_array($tsSetup)) {
            $setup = $service->convertTypoScriptArrayToPlainArray($manager->getTypoScriptSetup());
            $txExtensionName = 'tx_' . \strtolower($this->extensionName);
            if (isset($setup['plugin'][$txExtensionName])) {
                $settings = $setup['plugin'][$txExtensionName];
            }
        }

        return $settings;
    }

    /**
     * Gets the extension's backend settings.
     *
     * @return array
     */
    private function getBESettings()
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

    /**
     * Gets the extension configuration.
     *
     * @return array
     */
//    private function getExtensionConfiguration()
//    {
//        // load configurations
//        $feController = $GLOBALS['TSFE'];
//        /* @var $feController \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController */
//        $extensionKey = self::getExtensionKeyByExtensionName($this->extensionName);
//        $configuration = json_decode($feController->TYPO3_CONF_VARS['EXT'][$extensionKey]);
//
//        return $configuration;
//    }
}
