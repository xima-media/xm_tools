<?php

namespace Xima\XmTools\ViewHelpers;

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

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder;
use TYPO3\CMS\Fluid\Core\ViewHelper\Exception;
use TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper;

/**
 * Erstellt ein <img /> mit "data-srcset"-Attribut für den responsiven Ansatz mit JavaScript.
 *
 * @author Sebastian Gierth <sgi@xima.de>
 */
class ResponsiveImageViewHelper extends ImageViewHelper
{
    /**
     * @var array
     */
    protected $settings = null;

    /**
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function initialize()
    {
        parent::initialize();
        $configurationManager = $this->objectManager->get(ConfigurationManager::class);
        $this->settings = $configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS,
            'XmTools');
    }

    /**
     * @param null $src Pfad zu der Datei. Hier kann auch mit EXT: gearbeitet werden, da es sich hier um ein IMG_RESOURCE handelt.
     * @param string $width width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param string $height height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param integer $minWidth minimum width of the image
     * @param integer $minHeight minimum height of the image
     * @param integer $maxWidth maximum width of the image
     * @param integer $maxHeight maximum height of the image
     * @param bool $treatIdAsReference Wenn TRUE, dann wird die Angabe bei src als sys_file_reference interpretiert. Wenn FALSE als sys_file oder Dateipfad.
     * @param FileInterface|AbstractFileFolder $image Ein FAL-Objekt.
     * @param array $sizes Größenangaben zur Erstellung verschiedener Bildgrößen der Form {width: {0: 100, 1: 200}}.
     * @param null $crop Wenn FALSE, dann wird das Cropping-Verhalten, das in FileReference definiert ist, überschrieben.
     * @param bool $absolute Absoluter Pfad zum Bild.
     * @return string
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     */
    public function render(
        $src = null,
        $width = null,
        $height = null,
        $minWidth = null,
        $minHeight = null,
        $maxWidth = null,
        $maxHeight = null,
        $treatIdAsReference = false,
        $image = null,
        $sizes = array(),
        $crop = null,
        $absolute = false
    ) {
        if (is_null($src) && is_null($image) || !is_null($src) && !is_null($image)) {
            throw new Exception('You must either specify a string src or a File object.', 1450184864);
        }

        if (empty($sizes)) {
            throw new Exception('You must specify at least one size. Like sizes="{width: {0: 100}}".', 1450184865);
        }

        $typo3Version = VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getCurrentTypo3Version());

        $image = $this->imageService->getImage($src, $image, $treatIdAsReference);

        if ($crop === null) {
            $crop = ($image instanceof FileReference && $image->hasProperty('crop')) ? $image->getProperty('crop') : null;
        }

        if ($this->isImageRegenerationRequired($image)) {
            $srcset = array();
            $setHeight = false;
            $correspondingHeights = false;
            $fixedHeight = null;
            if (isset($sizes['height']) && is_array($sizes['height']) && !empty($sizes['height'])) {
                $setHeight = true;
                if (count($sizes['width']) == count($sizes['height'])) {
                    // we have a corresponding height to each of the widths
                    $correspondingHeights = true;
                } else {
                    // we assume to have one fixed height for all of the widths
                    $fixedHeight = array_shift($sizes['height']);
                }
            }

            foreach ($sizes['width'] as $key => $width) {

                // Default mode is scaling (= m)
                $mode = 'm';
                if (isset($sizes['mode']) && in_array($sizes['mode'], ['c', 'm'])) {
                    $mode = $sizes['mode'];
                }

                $processingInstructions = array(
                    'width' => $width,
                );

                if (isset($sizes['ratio'])) {
                    // calculate the corresponding height
                    $processingInstructions['height'] = round($width / $sizes['ratio']) . $mode;
                } elseif ($setHeight) {
                    if ($correspondingHeights) {
                        if ($sizes['height'][$key] != 'auto') { // if set to 'auto' the original ratio will be preserved
                            // set specified height
                            $processingInstructions['height'] = $sizes['height'][$key];
                        }
                    } elseif (!empty($fixedHeight)) {
                        $processingInstructions['height'] = $fixedHeight;
                    }
                    $processingInstructions['height'] .= $mode;
                }

                $processingInstructions['width'] .= $mode;

                if ($image instanceof FileInterface && $image->hasProperty('focus_point_x')) {
                    // take the focuspoint into account
                    $focus_point_x = $image->getProperty('focus_point_x');
                    $processingInstructions['width'] .= ((int)$focus_point_x > 0 ? '+' . $focus_point_x : '-' . abs($focus_point_x));
                    $focus_point_y = $image->getProperty('focus_point_y');
                    $processingInstructions['height'] .= ((int)$focus_point_y > 0 ? '-' . $focus_point_y : '+' . abs($focus_point_y));
                }

                if ($typo3Version >= 7006000) {
                    $processingInstructions['crop'] = $crop;
                }

                $processedImage = $this->imageService->applyProcessingInstructions($image, $processingInstructions);

                if ($typo3Version >= 7006000) {
                    $imageUri = $this->imageService->getImageUri($processedImage, $absolute);
                } else {
                    $imageUri = $this->imageService->getImageUri($processedImage);
                }

                $srcset[] = $imageUri . ' ' . $processedImage->getProperty('width') . 'w';
            }

            $this->tag->addAttribute('src',
                'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==');
            $this->tag->addAttribute('data-srcset', implode(',', $srcset));
        } else {
            $this->tag->addAttribute('src', $image->getPublicUrl());
        }

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

    /**
     * @param FileInterface $image
     * @return bool
     */
    protected function isImageRegenerationRequired($image)
    {
        if (method_exists($image,
                'getExtension') && isset($this->settings['viewHelpers']['responsiveImage']['dontRegenerateFileFormats'])) {
            $fileExt = $image->getExtension();
            $excluded = GeneralUtility::trimExplode(',',
                $this->settings['viewHelpers']['responsiveImage']['dontRegenerateFileFormats']);

            return !in_array($fileExt, $excluded);
        }

        return true;
    }
}
