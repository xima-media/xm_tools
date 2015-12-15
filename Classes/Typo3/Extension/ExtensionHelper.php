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

    /**
     * @param string $extensionName
     *
     * @return \Xima\XmTools\Classes\Typo3\Model\Extension
     */
    public function getExtension($extensionName = null)
    {
        $extension = null;
        $configuration = array();

        if (TYPO3_MODE === 'FE') {
            if (is_null($extensionName)) {
                //do this only for frontend extensions that call directly (in $configuration ['extensionName'])
                //get the extensions name that causes the call
                $configuration = $this->configurationManagerInterface->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
                $extensionName = $configuration ['extensionName'];
            } else {
                $configuration = $this->getConfigurationFE($extensionName);
            }
        } elseif (TYPO3_MODE === 'BE' && !is_null($extensionName)) {
            $configuration = $this->getConfigurationBE($extensionName);
        }

        //now create the demanded extension
        if (!is_null($extensionName)) {
            $extensionKey = self::getExtensionKeyByExtensionName($extensionName);
            if (in_array($extensionKey, ExtensionManagementUtility::getLoadedExtensionListArray())) {
                $extension = new Extension();

                /* @var $extension \Xima\XmTools\Classes\Typo3\Model\Extension */
                $extension->setName($extensionName);
                $extension->setKey($extensionKey);
                $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$extensionKey]);
                $extension->setConfiguration($extConf);
                if (isset($configuration['settings'])) {
                    $extension->setSettings($configuration['settings']);
                }
                $extension->setRelPath(ExtensionManagementUtility::siteRelPath($extension->getKey()));
                $extension->setExtPath(ExtensionManagementUtility::extPath($extension->getKey()));
            }
        }

        return $extension;
    }

    /**
     * @param string $extensionName
     *
     * converts a Typo3 extension name to the extension key
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

    private function getConfigurationBE($extensionName)
    {
        // load configurations before accessing ypoScriptSetupCache!
        $this->configurationManagerInterface->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);

        $manager = GeneralUtility::makeInstance("TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager");
        $service = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Service\TypoScriptService');

        // access the typoscript cache
        $arrOfObj = (array) $manager;

        //$arrKeys = array_keys($arrOfObj);
        $config = $arrOfObj["\0*\0typoScriptSetupCache"];

        $setup = $service->convertTypoScriptArrayToPlainArray(array_pop($config));
        $configuration = $setup['plugin']['tx_'.\strtolower($extensionName)];

        return $configuration;
    }

    private function getConfigurationFE($extensionName)
    {
        // load configurations
        $manager = GeneralUtility::makeInstance("TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager");
        $service = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Service\TypoScriptService');

        $setup = $service->convertTypoScriptArrayToPlainArray($manager->getTypoScriptSetup());
        $configuration = $setup['plugin']['tx_'.\strtolower($extensionName)];

        return $configuration;
    }
}
