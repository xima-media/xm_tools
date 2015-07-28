<?php

namespace Xima\XmTools\Classes\Typo3\Cache;

/**
 * Stores and retrieves data in a file.
 *
 * @author Wolfram Eberius <woe@xima.de>
 */
class CacheManager
{
    const BASE_PATH = 'typo3temp/Cache/Data/';

    const I10N_DIR_NAME = 'l10n';
    const EXTENSION_DIR_NAME = 'ext';
    const API_DIR_NAME = 'api';

    protected $path;

    public function __construct()
    {
        $this->path = self::BASE_PATH;
    }

    /**
     * Returns file contents. If file is younger than one day returns its value, otherwise false.
     *
     * @param string $filename
     *
     * @return string|bool
     */
    public function get($fileName)
    {
        $filePath = $this->getAbsoluteFilePath($fileName);

        if (is_readable($filePath) && $this->isFileValid($filePath)) {
            return file_get_contents($filePath);
        }

        return false;
    }

    public function write($fileName, $content)
    {
        $filePath = $this->getAbsoluteFilePath($fileName);

        return file_put_contents($filePath, $content);
    }

    public function setPath($path)
    {
        $this->path = self::BASE_PATH.$path.'/';

        if (!is_dir($this->getAbsolutePath())) {
            return mkdir($this->getAbsolutePath(), 0777, true);
        }

        return true;
    }

    protected function clearCache()
    {
        //check if the files to be deleted are in the temp folder but path is not the temp folder itself
        if (0 === strpos($this->path, self::BASE_PATH) && $this->path != self::BASE_PATH) {
            // get all file names
            $files = glob($this->getAbsolutePath().'*', GLOB_BRACE);
            foreach ($files as $file) {
                if (is_file($file)) {
                    // delete file
                    unlink($file);
                }
            }

            return true;
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
        return $this->path.$this->sanitizeFileName($fileName);
    }

    protected function sanitizeFileName($fileName)
    {
        return preg_replace('/[^a-zA-Z0-9.]+/', '_', $fileName);
    }

    protected function getPath()
    {
        return $this->path;
    }

    private function isFileValid($filePath)
    {
        return (date('F d Y', strtotime('today')) == date('F d Y', filemtime($filePath)));
    }

    private function getAbsoluteFilePath($fileName)
    {
        return PATH_site.$this->getFilePath($fileName);
    }

    private function getAbsolutePath()
    {
        return PATH_site.$this->path;
    }
}
