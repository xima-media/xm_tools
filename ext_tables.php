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
