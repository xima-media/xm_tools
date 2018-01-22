<?php
namespace Xima\XmTools\ViewHelpers\Media;

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

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class EmbedUrlViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('file', 'FileInterface|AbstractFileFolder|array', 'given media file', true);
    }
    /**
     * Return embed URL of a given media file
     *
     * @return string Media URL
     */
    public function render()
    {
        $file = $this->arguments['file'];

        if (is_array($file)) {
            if (array_key_exists('id', $file)) {
                $resourceFactory = ResourceFactory::getInstance();
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
                $url = sprintf('https://www.youtube.com/embed/%s', $mediaId);
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
