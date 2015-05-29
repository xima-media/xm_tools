<?php

namespace Xima\XmTools\Classes\Typo3\Cache;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Stores and retrieves api data in a file, file name is created using md5.
 *
 * @author Wolfram Eberius <woe@xima.de>
 */
class ApiCacheManager extends ExtensionCacheManager
{
    public function setPath($path)
    {
        $path .= '/'.CacheManager::API_DIR_NAME;

        return parent::setPath($path);
    }

    public function clear()
    {
        $extensionKey = $_GET['extension_key'];
        if (0 === strpos($extensionKey, 'xm_')) {
            $this->setPath($extensionKey);

            return $this->clearCache();
        }

        return false;
    }

    /**
     * Creates file name by replacing special chars.
     *
     * @param string $filename
     *
     * @return string
     */
    public function getFilePath($fileName)
    {
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $services = $objectManager->get('Xima\XmTools\Classes\Typo3\Services');
        /* @var $services \Xima\XmTools\Classes\Typo3\Services */

        $fileName = $this->sanitizeFileName($fileName);

        if (!$services->getExtensionManager()->getXmTools()->getSettings()['devModeIsEnabled']) {
            $fileName = md5($fileName);
        }

        /* @var $services \Xima\XmTools\Classes\Typo3\Services */
        $filePath = $this->path.$fileName;

        return $filePath;
    }
}
