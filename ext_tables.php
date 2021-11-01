<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/**
 * CSS skins for backend
 */
$GLOBALS['TBE_STYLES']['skins']['xm_tools'] = [
    'name' => 'XIMA Tools Backend Skin',
    'stylesheetDirectories' => [
        'visual' => 'EXT:xm_tools/Resources/Public/Backend/Css/'
    ]
];

$backendMarkingEnabled = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)
    ->get('xm_tools', 'backendMarkingEnabled');

if ((bool)$backendMarkingEnabled) {
    $appContext = \TYPO3\CMS\Core\Core\Environment::getContext();
    if (stristr($appContext, 'staging') || stristr($appContext, 'stage') || stristr($appContext, 'testing')) {
        $GLOBALS['TBE_STYLES']['skins']['xm_tools']['stylesheetDirectories'] += ['EXT:xm_tools/Resources/Public/Backend/Css/Staging'];
    } elseif (stristr($appContext, 'development')) {
        $GLOBALS['TBE_STYLES']['skins']['xm_tools']['stylesheetDirectories'] += ['EXT:xm_tools/Resources/Public/Backend/Css/Dev'];
    }
}
