<?php

namespace Xima\XmTools\Classes\ViewHelpers;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Kevin Kojtschke <kko@xima.de>, XIMA MEDIA GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

class ResponsiveImageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument('image', 'mixed', '', true)
            ->registerArgument('sizes', 'array', '', false)
            ->registerArgument('alt', 'string', '', false)
            ->registerArgument('title', 'string', '', false);
    }

    /**
     * Erzeugt Bildvarianten für verschiedene Medientypen.
     *
     * @return string
     */
    public function render()
    {
        $output = '';

        if (!$this->arguments['image'] || is_null($this->arguments['image'])) {
            return '';
        }

        try {
            if (!isset($this->arguments['sizes']) || empty($this->arguments['sizes'])) {
                $this->arguments['sizes'] = $this->templateVariableContainer->get('settings')['responsiveSizes'];
            }

            $output = '<picture>';
            $output .= '<!--[if IE 9]><video style="display: none;"><![endif]-->';

            $originalFilePath = '/fileadmin' . $this->arguments['image']->getIdentifier();
            $originalInternalFilePath = PATH_site . $originalFilePath;
            $originalFileName = $this->arguments['image']->getName();
            $parts = explode('.', $originalFileName);
            $originalFileNameExt = $parts[count($parts) - 1];

            list($originalWidth, $originalHeight) = getimagesize($originalInternalFilePath);

            switch ($this->arguments['image']->getMimeType()) {
                case 'image/jpeg':
                    $originalImg = imagecreatefromjpeg($originalInternalFilePath);
                    break;
                case 'image/png':
                    $originalImg = imagecreatefrompng($originalInternalFilePath);
                    break;
                case 'image/gif':
                    $originalImg = imagecreatefromgif($originalInternalFilePath);
                    break;
                default:
                    $originalImg = null;
            }

            $processedFileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ProcessedFileRepository');

            foreach ($this->arguments['sizes'] as $size) {
                if (count($size) != 2) {
                    continue;
                }

                list($media, $maxWidth) = $size;

                if ($originalWidth <= $maxWidth) {
                    // Breite des Bildes passt in die angegebene Maximalbreite
                    $filePath = $originalFilePath;
                } else {
                    $processedFiles = $processedFileRepository->findAllByOriginalFile($this->arguments['image']);

                    $filePath = null;
                    $filePathAbsolute = null;

                    if (!empty($processedFiles)) {
                        foreach ($processedFiles as $file) {
                            if ($file->getProperty('width') == $maxWidth) {
                                $filePath = '/fileadmin' . $file->getIdentifier();
                                $filePathAbsolute = PATH_site . 'fileadmin' . $file->getIdentifier();
                            }
                        }
                    }

                    if (!is_readable($filePathAbsolute)) {
                        // neue Bildvariante anlegen
                        if (null != $originalImg) {
                            $newHeight = $originalHeight * $maxWidth / $originalWidth;

                            $newFile = new \TYPO3\CMS\Core\Resource\ProcessedFile($this->arguments['image'], 'Image.CropScaleMask', array(
                                'fileExtension' => $originalFileNameExt,
                                'width' => $maxWidth,
                                'height' => $newHeight,
                            ));

                            $checksum = $newFile->calculateChecksum();

                            $newImg = imagecreatetruecolor($maxWidth, $newHeight);

                            imagesavealpha($newImg, true);
                            $color = imagecolorallocatealpha($newImg, 0x00, 0x00, 0x00, 127);
                            imagefill($newImg, 0, 0, $color);

                            imagecopyresized($newImg, $originalImg, 0, 0, 0, 0, $maxWidth, $newHeight, $originalWidth, $originalHeight);
                            $newFileName = preg_replace('~\.' . $originalFileNameExt . '$~', '_' . $checksum . '.' . $originalFileNameExt, $originalFileName);
                            $newIdentifier = '/_processed_/' . $newFileName;
                            $newFilePath = '/fileadmin' . $newIdentifier;
                            $newInternalFilePath = PATH_site . $newFilePath;

                            switch ($this->arguments['image']->getMimeType()) {
                                case 'image/jpeg':
                                    imagejpeg($newImg, $newInternalFilePath);
                                    break;
                                case 'image/png':
                                    imagepng($newImg, $newInternalFilePath);
                                    break;
                                case 'image/gif':
                                    imagegif($newImg, $newInternalFilePath);
                                    break;
                            }
                            imagedestroy($newImg);

                            // neuen Datensatz in sys_file_processedfile einfügen
                            $GLOBALS['TYPO3_DB']->exec_INSERTquery('sys_file_processedfile', array(
                                'tstamp' => time(),
                                'storage' => 1,
                                'original' => $this->arguments['image']->getUid(),
                                'identifier' => $newIdentifier,
                                'name' => $newFileName,
                                'configuration' => 'a:0:{}',
                                'configurationsha1' => '8739602554c7f3241958e3cc9b57fdecb474d508',
                                'originalfilesha1' => $this->arguments['image']->getSha1(),
                                'task_type' => 'Image.CropScaleMask',
                                'checksum' => $checksum,
                                'width' => $maxWidth,
                                'height' => $newHeight,
                            ));

                            unset($newFile);

                            $filePath = $newFilePath;
                        } else {
                            $filePath = $originalFilePath;
                        }
                    }
                }

                $output .= '<source media="' . $media . '" srcset="' . $filePath . '">';
            }

            unset($processedFileRepository);

            if (null != $originalImg) {
                imagedestroy($originalImg);
            }

            $metadata = $this->arguments['image']->_getMetadata();

            $output .= '<!--[if IE 9]></video><![endif]-->';
            $output .= '<img alt="' . $metadata['alternative'] . '" title="' . $metadata['title'] . '" srcset="' . $originalFilePath . '">';
            $output .= '</picture>';
        } catch (\Exception $e) {
        }

        return $output;
    }
}
