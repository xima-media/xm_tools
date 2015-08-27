<?php

namespace Xima\XmTools\Classes\Typo3\Helper;

use \TYPO3\CMS\Backend\Utility\BackendUtility;
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use \TYPO3\CMS\Frontend\Utility\EidUtility;
use \TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class TsfeHelper
 *
 * @author Steve Lenz <steve.lenz@xima.de>
 * @package TYPO3 6.2.x
 * @version 1.0.0
 */
class TsfeHelper
{

    /**
     * Initialize TSFE
     *
     * With realurl 1.12.x support
     *
     * @param int $pid Page ID
     * @param int $typeNum
     */
    public static function initTSFE($pid = 1, $typeNum = 0)
    {
        EidUtility::initTCA();
        if (!is_object($GLOBALS['TT'])) {
            $GLOBALS['TT'] = new NullTimeTracker;
            $GLOBALS['TT']->start();
        }
        $GLOBALS['TSFE'] = GeneralUtility::makeInstance(
            'TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController',
            $GLOBALS['TYPO3_CONF_VARS'],
            $pid,
            $typeNum
        );
        $GLOBALS['TSFE']->connectToDB();
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();

        if (ExtensionManagementUtility::isLoaded('realurl')) {
            $rootline = BackendUtility::BEgetRootLine($pid);
            $host = BackendUtility::firstDomainRecord($rootline);
            $_SERVER['HTTP_HOST'] = $host;
        }
    }

    /**
     * Generates a URL by typoLink_URL
     *
     * @param int $pid Page ID
     * @param array $conf TypoScript properties for typolink
     * @return mixed
     */
    public static function generateTypoLinkUrl($pid = 1, array $conf = array())
    {
        self::initTSFE($pid);
        $cObj = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');

        return $cObj->typolink_URL($conf);
    }

}