<?php
namespace Xima\XmTools\Classes\ViewHelpers;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 OpenSource Team, XIMA MEDIA GmbH, osdev@xima.de
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

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\Exception;
use TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder;

/**
 * Erstellt ein <img /> mit "data-srcset"-Attribut für den responsiven Ansatz mit JavaScript.
 *
 * @author Sebastian Gierth <sgi@xima.de>
 */
class ResponsiveImageViewHelper extends ImageViewHelper
{
    /**
     * @param null $src Pfad zu der Datei. Hier kann auch mit EXT: gearbeitet werden, da es sich hier um ein IMG_RESOURCE handelt.
     * @param array $sizes Größenangaben zur Erstellung verschiedener Bildgrößen der Form {width: {0: 100, 1: 200}}.
     * @param bool $treatIdAsReference Wenn TRUE, dann wird die Angabe bei src als sys_file_reference interpretiert. Wenn FALSE als sys_file oder Dateipfad.
     * @param FileInterface|AbstractFileFolder $image Ein FAL-Objekt.
     * @param null $crop Wenn FALSE, dann wird das Cropping-Verhalten, das in FileReference definiert ist, überschrieben.
     * @param bool $absolute Absoluter Pfad zum Bild.
     * @return string
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     */
    public function render($src = null, $sizes = array(), $treatIdAsReference = false, $image = null, $crop = null, $absolute = false)
    {
        if (is_null($src) && is_null($image) || !is_null($src) && !is_null($image)) {
            throw new Exception('You must either specify a string src or a File object.', 1450184864);
        }

        if (empty($sizes)){
            throw new Exception('You must specify at least one size. Like sizes="{width: {0: 100}}".', 1450184865);
        }

        $typo3Version = VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getCurrentTypo3Version());

        $image = $this->imageService->getImage($src, $image, $treatIdAsReference);

        if ($crop === null) {
            $crop = ($image instanceof FileReference && $image->hasProperty('crop')) ? $image->getProperty('crop') : null;
        }

        $srcset = array();
        foreach ($sizes['width'] as $width) {

            $processingInstructions = array(
                'width'  => $width,
            );

            if ($typo3Version >= 7006000){
                $processingInstructions['crop'] = $crop;
            }

            $processedImage = $this->imageService->applyProcessingInstructions($image, $processingInstructions);

            if ($typo3Version >= 7006000){
                $imageUri = $this->imageService->getImageUri($processedImage, $absolute);
            }
            else {
                $imageUri = $this->imageService->getImageUri($processedImage);
            }

            $srcset[] = $imageUri . ' ' . $processedImage->getProperty('width') . 'w';
        }

        $this->tag->addAttribute('src', 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==');
        $this->tag->addAttribute('data-srcset', implode(',', $srcset));

        $alt = $image->getProperty('alternative');
        $title = $image->getProperty('title');

        if (empty($this->arguments['alt'])) {
            $this->tag->addAttribute('alt', $alt);
        }
        if (empty($this->arguments['title']) && $title) {
            $this->tag->addAttribute('title', $title);
        }

        return $this->tag->render();
    }
}
