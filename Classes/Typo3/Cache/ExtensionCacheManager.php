<?php
namespace Xima\XmTools\Classes\Typo3\Cache;

/**
 * Stores and retrieves api data in a file.
 *
 * @package xm_tools
 * @author Wolfram Eberius <woe@xima.de>
 *
 */
class ExtensionCacheManager extends CacheManager
{
    public function setPath($path) {
    
        $path = CacheManager::EXTENSION_DIR_NAME.'/'.$path;
    
        return parent::setPath($path);
    }
}