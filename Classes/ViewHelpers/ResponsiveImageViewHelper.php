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

use TYPO3\CMS\Core\Imaging\ImageManipulation\Area;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;

/**
 * Erstellt ein <img /> mit "data-srcset"-Attribut für den responsiven Ansatz mit JavaScript.
 *
 * @author Sebastian Gierth <sgi@xima.de>
 * @see https://github.com/xima-media/xm_tools/wiki/ResponsiveImageViewHelper
 */
class ResponsiveImageViewHelper extends ImageViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('sizes', 'array', 'Größenangaben zur Erstellung verschiedener Bildgrößen der Form {width: {0: 100, 1: 200}}', true);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function render() {

        if (is_null($this->arguments['src']) && is_null($this->arguments['image']) || !is_null($this->arguments['src']) && !is_null($this->arguments['image'])) {
            throw new Exception('You must either specify a string src or a File object.', 1450184864);
        }

        if (empty($this->arguments['sizes'])) {
            throw new Exception('You must specify at least one size. Like sizes="{width: {0: 100}}".', 1450184865);
        }

        $typo3Version = VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getCurrentTypo3Version());

        $image = $this->imageService->getImage($this->arguments['src'], $this->arguments['image'], $this->arguments['treatIdAsReference']);

        $cropString = $this->arguments['crop'];
        if ($cropString === null && $image->hasProperty('crop') && $image->getProperty('crop')) {
            $cropString = $image->getProperty('crop');
        }

        $srcset = array();
        $setHeight = false;
        $correspondingHeights = false;
        $fixedHeight = null;
        $sizes = $this->arguments['sizes'];
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

        $smallestWidth = reset($sizes['width']);

        foreach ($sizes['width'] as $key => $width) {

            // Default mode is scaling (= m)
            $mode = 'm';
            if (isset($sizes['mode']) && in_array($sizes['mode'], ['c','m'])) {
                $mode = $sizes['mode'];
            }

            $processingInstructions = array(
                'width' => $width,
            );

            if (isset($sizes['ratio'])) {
                // calculate the corresponding height
                $processingInstructions['height'] = round($width/$sizes['ratio']) . $mode;
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

            if ($typo3Version >= 8006000) {
                $cropVariantCollection = CropVariantCollection::create((string) $cropString);
                $cropVariant = $this->arguments['cropVariant'] ?: 'default';
                $cropArea = $cropVariantCollection->getCropArea($cropVariant);
                $focusArea = $cropVariantCollection->getFocusArea($cropVariant);

                $processingInstructions['crop'] = $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image);

                if ($mode === 'c' && !$focusArea->isEmpty()) {
                    $processingInstructions = $this->applyCropShiftingInstructionsBasedOnFocusArea($processingInstructions,
                        $image, $cropArea, $focusArea);
                }
            }
            else if ($typo3Version >= 7006000){
                $processingInstructions['crop'] = $cropString;
            }

            $processedImage = $this->imageService->applyProcessingInstructions($image, $processingInstructions);

            if ($typo3Version >= 7006000) {
                $imageUri = $this->imageService->getImageUri($processedImage, $this->arguments['absolute']);
            } else {
                $imageUri = $this->imageService->getImageUri($processedImage);
            }

            $width = $processedImage->getProperty('width') ?: '0';
            $srcset[] = $imageUri . ' ' . $width . 'w';

            if ($width < $smallestWidth){
                $smallestWidth = $width;
            }
        }

        $dataSrcset = implode(',', $srcset);

        $this->tag->addAttribute('src', 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==');
        $this->tag->addAttribute('data-srcset', $dataSrcset);

        $alt = $image->getProperty('alternative');
        $title = $image->getProperty('title');

        if (empty($this->arguments['alt'])) {
            $this->tag->addAttribute('alt', $alt);
        }
        if (empty($this->arguments['title']) && $title) {
            $this->tag->addAttribute('title', $title);
        }

        if (preg_match('~([^\s,]+)\s+'. $smallestWidth .'w~', $dataSrcset, $matches) !== false){

            $defaultImgTag = new TagBuilder($this->tagName);
            $defaultImgTag->addAttribute('src', $matches[1]);
            $defaultImgTag->addAttribute('alt', $this->tag->getAttribute('alt'));
            $defaultImgTag->addAttribute('title', $this->tag->getAttribute('title'));
            $defaultImgTag->addAttribute('style', 'width: 100%; max-width: 100%; height: auto;');

            $noscriptTag = sprintf('<noscript>%s</noscript>', $defaultImgTag->render());
        }

        return $this->tag->render() . ($noscriptTag ?? '');
    }

    /**
     * Add shifting values to the width and height values of the processing instructions array
     * to make sure the focus area is within the resulting image
     * For explanation and examples see:
     * https://docs.typo3.org/m/typo3/reference-typoscript/master/en-us/Functions/Imgresource.html#width
     *
     * @param array $processingInstructions
     * @param FileInterface $image
     * @param Area $cropArea
     * @param Area $focusArea
     * @return array
     */
    protected function applyCropShiftingInstructionsBasedOnFocusArea(
        array $processingInstructions,
        FileInterface $image,
        Area $cropArea,
        Area $focusArea
    ) {
        $cropAreaAbsolute = $cropArea->makeAbsoluteBasedOnFile($image);

        // First the Y dimension:

        $hC = $cropAreaAbsolute->asArray()['height'];       // absolute height of cropping area
        $yFrel = $focusArea->asArray()['y'];                // relative y position of focus area within cropping area
        $hZ = substr($processingInstructions['height'], 0, -1);    // target height of responsive image

        $yF = $hC * $yFrel;                                 // absolute y position of focus area within cropping area

        // calculation of the absolute shifting value ($sY) for the cropping instructions
        if ($yFrel < 0.5) {
            $direction = '-';

            $sY = $hC / 2 - $hZ / 2 - $yF;
        } else {
            $direction = '+';
            $hF = $focusArea->asArray()['height'] * $cropAreaAbsolute->asArray()['height'];
            $yFrest = $hC - $yF - $hF;

            $sY = $hC / 2 - $hZ / 2 - $yFrest;
        }

        // make the shifting value relative
        $sYrel = 0;
        // avoid division by zero
        if ($hC != $hZ) {
            $sYrel = round(($sY / ($hC / 2 - $hZ / 2)) * 100);
        }

        if ($sYrel > 0) {
            $processingInstructions['height'] .= $direction . $sYrel;
        }

        // Second the X dimension:

        $wC = $cropAreaAbsolute->asArray()['width'];       // absolute width of cropping area
        $xFrel = $focusArea->asArray()['x'];        // relative x position of focus area within cropping area
        $wZ = substr($processingInstructions['width'], 0, -1);    // target width of responsive image

        $xF = $wC * $xFrel;                         // absolute x position of focus area within cropping area

        // calculation of the absolute shifting value ($sX) for the cropping instructions
        if ($xFrel < 0.5) {
            $direction = '-';

            $sX = $wC / 2 - $wZ / 2 - $xF;
        } else {
            $direction = '+';
            $wF = $focusArea->asArray()['width'] * $cropAreaAbsolute->asArray()['width'];
            $xFrest = $wC - $xF - $wF;

            $sX = $wC / 2 - $wZ / 2 - $xFrest;
        }

        // make the shifting value relative
        $sXrel = 0;
        // avoid division by zero
        if ($wC != $wZ) {
            $sXrel = round(($sX / ($wC / 2 - $wZ / 2)) * 100);
        }

        if ($sXrel > 0) {
            $processingInstructions['width'] .= $direction . $sXrel;
        }

        return $processingInstructions;
    }
}
