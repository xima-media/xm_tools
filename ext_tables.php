<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

/**
 * CSS skins for backend
 */
$GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['xm_tools'] = 'EXT:xm_tools/Resources/Public/Backend/Css';

$backendMarkingEnabled = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)
    ->get('xm_tools', 'backendMarkingEnabled');

if ((bool)$backendMarkingEnabled) {
    $appContext = \TYPO3\CMS\Core\Core\Environment::getContext();
    if (stripos($appContext, 'staging') !== false || stripos($appContext, 'stage') !== false || stripos(
        $appContext,
        'testing'
    ) !== false) {
        $GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['xm_tools_staging'] = 'EXT:xm_tools/Resources/Public/Backend/Css/Staging';
    } elseif (stripos($appContext, 'development') !== false) {
        $GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['xm_tools_dev'] = 'EXT:xm_tools/Resources/Public/Backend/Css/Dev';
    }
}
