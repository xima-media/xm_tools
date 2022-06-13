<?php

if (!defined('TYPO3')) {
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
    if (stripos($appContext, 'staging') !== false || stripos($appContext, 'stage') !== false || stripos($appContext,
            'testing') !== false) {
        $GLOBALS['TBE_STYLES']['skins']['xm_tools']['stylesheetDirectories'] += ['EXT:xm_tools/Resources/Public/Backend/Css/Staging'];
    } elseif (stripos($appContext, 'development') !== false) {
        $GLOBALS['TBE_STYLES']['skins']['xm_tools']['stylesheetDirectories'] += ['EXT:xm_tools/Resources/Public/Backend/Css/Dev'];
    }
}
