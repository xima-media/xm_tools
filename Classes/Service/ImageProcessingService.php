<?php declare(strict_types=1);


namespace Xima\XmTools\Service;


use TYPO3\CMS\Core\Imaging\ImageManipulation\Area;
use TYPO3\CMS\Core\Resource\FileInterface;

/**
 * Class ImageProcessingService
 * @package Xima\XmTools\Service
 */
class ImageProcessingService
{
    /**
     * Add shifting values to the width and height values of the processing instructions array
     * to make sure the focus area is within the resulting image
     * For explanation and examples see:
     * https://docs.typo3.org/m/typo3/reference-typoscript/10.4/en-us/Functions/Imgresource.html#width
     *
     * @param array $processingInstructions
     * @param FileInterface $image
     * @param Area $cropArea
     * @param Area $focusArea
     * @return array
     */
    public function applyCropShiftingInstructionsBasedOnFocusArea(
        array $processingInstructions,
        FileInterface $image,
        Area $cropArea,
        Area $focusArea
    ): array
    {
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
