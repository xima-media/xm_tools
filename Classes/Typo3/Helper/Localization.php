<?php

namespace Xima\XmTools\Classes\Typo3\Helper;

use Xima\XmTools\Classes\Typo3\Services;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmTools\Classes\Helper\Dictionary;
use Xima\XmTools\Classes\Typo3\Cache\CacheManager;

class Localization
{
    const XLIFF_DEFAULT_LANG = 'en';

    /**
     * Get translations of current extension, xm_tools and optional any more extensions. Supports conversion to js file and loading it as well.
     *
     * @param array $additionalExtensionNames Other extensions to get translations of.
     *
     * @return \Xima\XmTools\Classes\Helper\Dictionary An array of translations by key in the current language.
     */
    public static function getDictionary($additionalExtensionNames = array(), $lang = null)
    {
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

        $services = $objectManager->get('Xima\XmTools\Classes\Typo3\Services');
        /* @var $services \Xima\XmTools\Classes\Typo3\Services */

        $cacheManager = $objectManager->get('Xima\XmTools\Classes\Typo3\Cache\CacheManager');
        /* @var $cacheManager \Xima\XmTools\Classes\Typo3\Cache\CacheManager */

        $cacheManager->setPath(CacheManager::I10N_DIR_NAME);

        $extensions = array();

        //lang is the locale
        $lang = is_null($lang) ? $services->getLang() : $lang;
        //langKey is the lcoale, 'default' if 'en'
        $langKey = self::getLangKey($lang);

        //execute the extensions in the following order, global translations first, then local translations to allow for overriding
        array_push($extensions, $services->getExtensionManager()->getXmTools());

        $currentExtension = $services->getExtension();
        if (is_a($currentExtension, 'Xima\XmTools\Classes\Typo3\Model\Extension')) {
            array_push($extensions, $currentExtension);
        }

        foreach ($additionalExtensionNames as $extensionName) {
            $tmpExtension = $services->getExtensionManager()->getExtensionByName($extensionName);
            if (is_a($tmpExtension, 'Xima\XmTools\Classes\Typo3\Model\Extension')) {
                array_push($extensions, $tmpExtension);
            }
        }

        //if js support is enabled we need this
        $jsFileNamePrefix = ($langKey != Services::DEFAULT_LANG_STRING) ? $langKey.'.' : '';
        $jsFileName = $jsFileNamePrefix.'locallang.js';

        $translations = array();

        foreach ($extensions as $extension) {
            /* @var $extension \Xima\XmTools\Classes\Typo3\Model\Extension */
            $translations = array_merge($translations, $extension->getTranslations($lang));

            if ($services->getSettings()['jsL10nIsEnabled']) {
                $filename = $extension->getKey().'.'.$jsFileName;

                $filePath = $cacheManager->getFilePath($filename);

                if (!is_readable($filePath) || $services->getExtensionManager()->getXmTools()->getSettings()['devModeIsEnabled']) {
                    //generate js file
                    $translationStrings = array();
                    foreach ($extension->getTranslations($lang) as $key => $translation) {
                        $translationStrings[] = $key.':"'.$translation.'"';
                    }

                    $content = "if (typeof xmTools != \"undefined\")\n";
                    $content .= "{\n";
                    $content .= '  translations = {'.implode(',', $translationStrings)."};\n";
                    $content .= "  xmTools.addTranslations(translations);\n";
                    $content .= "  delete translations;\n";
                    $content .= '};';

                    //open and write to file
                    $cacheManager->write($filename, $content);
                }

                $services->includeJavaScript(array($filePath));
            }
        }

        $dictionary = new Dictionary();
        $dictionary->setTranslations($translations);

        return $dictionary;
    }

    /**
     * Helper method for development: list all available translations of the selected extensions, ordered alphabetically.
     */
    public static function printDictionary($additionalExtensionNames = array())
    {
        $translations = self::getDictionary($additionalExtensionNames);
        asort($translations);
        print_r($translations);
    }

    public static function getTranslations(\Xima\XmTools\Classes\Typo3\Model\Extension $extension, $lang)
    {
        $translations = array();

        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

        $services = $objectManager->get('Xima\XmTools\Classes\Typo3\Services');
        /* @var $services \Xima\XmTools\Classes\Typo3\Services */

        $cacheManager = $objectManager->get('Xima\XmTools\Classes\Typo3\Cache\CacheManager');
        /* @var $cacheManager \Xima\XmTools\Classes\Typo3\Cache\CacheManager */

        $cacheManager->setPath(CacheManager::I10N_DIR_NAME);

        //lang is the locale
        $lang = is_null($lang) ? $services->getLang() : $lang;
        //langKey is the lcoale, 'default' if 'en'
        $langKey = self::getLangKey($lang);

        //try to get xliff
        $filename = $extension->getExtPath().'Resources/Private/Language/locallang.xlf';

        if (file_exists($filename)) {
            //try to get from cache
            $cacheFileNamePrefix = ($langKey != Services::DEFAULT_LANG_STRING) ? $langKey.'.' : '';
            $cacheFileName = md5($extension->getKey().'.'.$cacheFileNamePrefix.'locallang');

            $translationsSerialized = $cacheManager->get($cacheFileName);
            if ($translationsSerialized && !$services->getExtensionManager()->getXmTools()->getSettings()['devModeIsEnabled']) {
                $translations = unserialize($translationsSerialized);
            } else {
                $result = array();
                try {
                    //parse translations in selected language
                    $parser = new \TYPO3\CMS\Core\Localization\Parser\XliffParser();
                    $result = $parser->getParsedData($filename, $langKey)[$langKey];
                } catch (\RuntimeException $e) {
                    //langauge file does not exist
                    $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
                    $logger = $objectManager->get('Xima\XmTools\Classes\Typo3\Logger');
                    $logger->log($e->getMessage(), $extension);

                    //use default language
                    $parser = new \TYPO3\CMS\Core\Localization\Parser\XliffParser();
                    $result = $parser->getParsedData($filename, Services::DEFAULT_LANG_STRING)[Services::DEFAULT_LANG_STRING];
                }

                foreach ($result as $key => $value) {
                    //replace dots by comma, otherwise it's an array in fluid
                    $translations[str_replace('.', '_', $key)] = $value[0]['target'];
                }

                $cacheManager->write($cacheFileName, serialize($translations));
            }
        }

        return $translations;
    }

    /**
     * Returns the current lang key, 'default' if 'en'
     * Specific return value for the XliffParser.
     *
     * @return string
     */
    public function getLangKey($lang)
    {
        $langKey = $lang;
        if ($lang == self::XLIFF_DEFAULT_LANG) {
            $langKey = Services::DEFAULT_LANG_STRING;
        }

        return $langKey;
    }
}
