<?php

namespace Xima\XmTools\ViewHelpers\Media;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2017 OpenSource Team, XIMA MEDIA GmbH, osdev@xima.de
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

use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class EmbedUrlViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('file', FileInterface::class, 'given media file');
        $this->registerArgument('fileAsArray', 'array', 'given media file as array');
    }

    /**
     * Return embed URL of a given media file
     *
     * @return string Media URL
     */
    public function render()
    {
        if ((is_null($this->arguments['file']) && is_null($this->arguments['fileAsArray'])) || (!is_null($this->arguments['file']) && !is_null($this->arguments['fileAsArray']))) {
            throw new Exception('You must either specify an array or a File object.',
                1535005991);
        }
        $file = $this->arguments['file'] ?: $this->arguments['fileAsArray'];

        if (empty($file)) {
            return '';
        }

        if (is_array($file)) {
            if (array_key_exists('id', $file)) {
                $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
                $file = $resourceFactory->getFileObjectFromCombinedIdentifier($file['id']);
            } else {
                return '';
            }
        }

        // get Resource Object (non ExtBase version)
        if (is_callable([$file, 'getOriginalResource'])) {
            // We have a domain model, so we need to fetch the FAL resource object from there
            $file = $file->getOriginalResource();
        }

        $mediaId = $file->getContents();

        switch ($file->getExtension()) {
            case 'youtube':
                $url = sprintf('https://www.youtube-nocookie.com/embed/%s', $mediaId);
                break;
            case 'vimeo':
                $url = sprintf('https://player.vimeo.com/video/%s', $mediaId);
                break;
            default:
                $url = '';
        }

        return $url;
    }
}
