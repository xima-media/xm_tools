<?php
// declare ajax action to remove extension caches
$TYPO3_CONF_VARS['BE']['AJAX']['xm_tools::clearCache'] = 'EXT:xm_tools/Classes/Typo3/Cache/ApiCacheManager.php:Xima\XmTools\Classes\Typo3\Cache\ApiCacheManager->clear';

// autoload xm_tools and vendors
$autoLoader = __DIR__.'/autoload.php';
if (is_readable($autoLoader)) {
    require_once $autoLoader;
}