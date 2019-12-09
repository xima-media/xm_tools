<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'XIMA Tools');

/**
 * CSS skins for backend
 */
$GLOBALS['TBE_STYLES']['skins']['xm_tools'] = [
    'name' => 'XIMA Tools Backend Skin',
    'stylesheetDirectories' => [
        'visual' => 'EXT:xm_tools/Resources/Public/Backend/Css/'
    ]
];

$configurationManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager::class);
$configurationManager->getDefaultBackendStoragePid();
$typoScriptSetup = $configurationManager->getTypoScriptSetup();

$showBackendMarking = $typoScriptSetup['module.']['tx_xmtools.']['settings.']['contextBackendMarking'];

if ((bool)$showBackendMarking) {
    $appContext = \TYPO3\CMS\Core\Utility\GeneralUtility::getApplicationContext();
    if (stristr($appContext, 'staging') || stristr($appContext, 'stage') || stristr($appContext, 'testing')) {
        $GLOBALS['TBE_STYLES']['skins']['xm_tools']['stylesheetDirectories'] += ['EXT:xm_tools/Resources/Public/Backend/Css/Staging'];
    } elseif (stristr($appContext, 'development')) {
        $GLOBALS['TBE_STYLES']['skins']['xm_tools']['stylesheetDirectories'] += ['EXT:xm_tools/Resources/Public/Backend/Css/Dev'];
    }
}
