<?php
// declare ajax action to remove extension caches
$TYPO3_CONF_VARS['BE']['AJAX']['xm_tools::clearCache'] = 'EXT:xm_tools/Classes/Typo3/Cache/ApiCacheManager.php:Xima\XmTools\Classes\Typo3\Cache\ApiCacheManager->clear';

$autoloadFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('xm_tools') . 'autoload.php';
require_once $autoloadFile;