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

use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;
use Xima\XmTools\Service\ImageProcessingService;

/**
 * Erstellt ein <img /> mit "data-srcset"-Attribut für den responsiven Ansatz mit JavaScript.
 *
 * @author Sebastian Gierth <sgi@xima.de>
 * @see https://github.com/xima-media/xm_tools/wiki/ResponsiveImageViewHelper
 */
class ResponsiveImageViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'img';

    protected ImageService $imageService;

    /**
     * @var array
     */
    protected array $settings = [];

    public function __construct()
    {
        parent::__construct();
        $this->imageService = GeneralUtility::makeInstance(ImageService::class);
    }

    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerUniversalTagAttributes();
        $this->registerTagAttribute('alt', 'string', 'Specifies an alternate text for an image', false);
        $this->registerTagAttribute(
            'ismap',
            'string',
            'Specifies an image as a server-side image-map. Rarely used. Look at usemap instead',
            false
        );
        $this->registerTagAttribute(
            'longdesc',
            'string',
            'Specifies the URL to a document that contains a long description of an image',
            false
        );
        $this->registerTagAttribute('usemap', 'string', 'Specifies an image as a client-side image-map', false);
        $this->registerTagAttribute(
            'loading',
            'string',
            'Native lazy-loading for images property. Can be "lazy", "eager" or "auto"',
            false
        );
        $this->registerTagAttribute(
            'decoding',
            'string',
            'Provides an image decoding hint to the browser. Can be "sync", "async" or "auto"',
            false
        );

        $this->registerArgument(
            'src',
            'string',
            'a path to a file, a combined FAL identifier or an uid (int). If $treatIdAsReference is set, the integer is considered the uid of the sys_file_reference record. If you already got a FAL object, consider using the $image parameter instead',
            false,
            ''
        );
        $this->registerArgument(
            'treatIdAsReference',
            'bool',
            'given src argument is a sys_file_reference record',
            false,
            false
        );
        $this->registerArgument(
            'image',
            'object',
            'a FAL object (\\TYPO3\\CMS\\Core\\Resource\\File or \\TYPO3\\CMS\\Core\\Resource\\FileReference)'
        );
        $this->registerArgument(
            'crop',
            'string|bool|array',
            'overrule cropping of image (setting to FALSE disables the cropping set in FileReference)'
        );
        $this->registerArgument(
            'cropVariant',
            'string',
            'select a cropping variant, in case multiple croppings have been specified or stored in FileReference',
            false,
            'default'
        );
        $this->registerArgument('fileExtension', 'string', 'Custom file extension to use');

        $this->registerArgument(
            'width',
            'string',
            'width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.'
        );
        $this->registerArgument(
            'height',
            'string',
            'height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.'
        );
        $this->registerArgument('minWidth', 'int', 'minimum width of the image');
        $this->registerArgument('minHeight', 'int', 'minimum height of the image');
        $this->registerArgument('maxWidth', 'int', 'maximum width of the image');
        $this->registerArgument('maxHeight', 'int', 'maximum height of the image');
        $this->registerArgument('absolute', 'bool', 'Force absolute URL', false, false);

        $this->registerArgument(
            'sizes',
            'array',
            'Größenangaben zur Erstellung verschiedener Bildgrößen der Form {width: {0: 100, 1: 200}}',
            true
        );
    }

    /**
     * @throws InvalidConfigurationTypeException
     */
    public function initialize()
    {
        parent::initialize();
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $this->settings = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'XmTools'
        );
    }

    /**
     * @return string
     * @throws Exception
     */
    public function render()
    {
        $src = (string)$this->arguments['src'];
        if (($src === '' && $this->arguments['image'] === null) || ($src !== '' && $this->arguments['image'] !== null)) {
            throw new Exception('You must either specify a string src or a File object.', 1382284106);
        }

        if (empty($this->arguments['sizes']['width'])) {
            throw new Exception('You must specify at least one size. Like sizes="{width: {0: 100}}".', 1450184865);
        }

        $typo3Version = VersionNumberUtility::convertVersionNumberToInteger(
            VersionNumberUtility::getCurrentTypo3Version()
        );

        $image = $this->imageService->getImage(
            $this->arguments['src'],
            $this->arguments['image'],
            $this->arguments['treatIdAsReference']
        );

        $cropString = $this->arguments['crop'];
        if ($cropString === null && $image->hasProperty('crop') && $image->getProperty('crop')) {
            $cropString = $image->getProperty('crop');
        }

        if ($this->isImageRegenerationRequired($image)) {
            $srcset = [];
            $setHeight = false;
            $correspondingHeights = false;
            $fixedHeight = null;
            $sizes = $this->arguments['sizes'];
            $sizes['height'] = $sizes['height'] ?? [];
            if (is_array($sizes['height']) && !empty($sizes['height'])) {
                $setHeight = true;
                if (count($sizes['width']) === count($sizes['height'])) {
                    // we have a corresponding height to each of the widths
                    $correspondingHeights = true;
                } else {
                    // we assume to have one fixed height for all the widths
                    $fixedHeight = array_shift($sizes['height']);
                }
            }

            $smallestWidth = reset($sizes['width']);

            foreach ($sizes['width'] as $key => $width) {
                // Default mode is scaling (= m)
                $mode = 'm';
                if (isset($sizes['mode']) && in_array($sizes['mode'], ['c', 'm'])) {
                    $mode = $sizes['mode'];
                }

                $processingInstructions = [
                    'width' => $width,
                    'height' => '',
                ];

                if (isset($sizes['ratio'])) {
                    // calculate the corresponding height
                    $processingInstructions['height'] = round($width / $sizes['ratio']) . $mode;
                } elseif ($setHeight) {
                    if ($correspondingHeights) {
                        if ($sizes['height'][$key] !== 'auto') { // if set to 'auto' the original ratio will be preserved
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
                    $processingInstructions['width'] .= ((int)$focus_point_x > 0 ? '+' . $focus_point_x : '-' . abs(
                        $focus_point_x
                    ));
                    $focus_point_y = $image->getProperty('focus_point_y');
                    if (isset($processingInstructions['height'])) {
                        $processingInstructions['height'] .= ((int)$focus_point_y > 0 ? '-' . $focus_point_y : '+' . abs(
                            $focus_point_y
                        ));
                    }
                }

                if ($typo3Version >= 8006000) {
                    if ($image instanceof FileInterface && $image->hasProperty('width')) {
                        $cropVariantCollection = CropVariantCollection::create((string)$cropString);
                        $cropVariant = $this->arguments['cropVariant'] ?: 'default';
                        $cropArea = $cropVariantCollection->getCropArea($cropVariant);
                        $focusArea = $cropVariantCollection->getFocusArea($cropVariant);
                        $processingInstructions['crop'] = $cropArea->isEmpty(
                        ) ? null : $cropArea->makeAbsoluteBasedOnFile($image);
                        if ($mode === 'c' && !$focusArea->isEmpty()) {
                            $imageProcessingService = GeneralUtility::makeInstance(ImageProcessingService::class);
                            $processingInstructions = $imageProcessingService
                                ->applyCropShiftingInstructionsBasedOnFocusArea(
                                    $processingInstructions,
                                    $image,
                                    $cropArea,
                                    $focusArea
                                );
                        }
                    }
                } elseif ($typo3Version >= 7006000) {
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

                if ($width < $smallestWidth) {
                    $smallestWidth = $width;
                }
            }

            $dataSrcset = implode(',', $srcset);

            $this->tag->addAttribute(
                'src',
                'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=='
            );
            $this->tag->addAttribute('data-srcset', $dataSrcset);

            if (preg_match('~([^\s,]+)\s+' . $smallestWidth . 'w~', $dataSrcset, $matches) !== false) {
                $defaultImgTag = new TagBuilder($this->tagName);
                $defaultImgTag->addAttribute(
                    'src',
                    $matches[1] ?? $this->imageService->getImageUri(
                        $image,
                        $this->arguments['absolute']
                    )
                );
                $defaultImgTag->addAttribute('alt', $this->tag->getAttribute('alt'));
                $defaultImgTag->addAttribute('title', $this->tag->getAttribute('title'));
                $defaultImgTag->addAttribute('style', 'width: 100%; max-width: 100%; height: auto;');

                $noscriptTag = sprintf('<noscript>%s</noscript>', $defaultImgTag->render());
            }
        } else {
            if ($typo3Version >= 7006000) {
                $imageUri = $this->imageService->getImageUri($image, $this->arguments['absolute']);
            } else {
                $imageUri = $this->imageService->getImageUri($image);
            }
            $this->tag->addAttribute('src', $imageUri);
            $this->tag->addAttribute('class', 'xm-responsive-images xm-responsive-images--excluded');
        }

        $alt = $image->getProperty('alternative');
        $title = $image->getProperty('title');

        if (empty($this->arguments['alt'])) {
            $this->tag->addAttribute('alt', $alt);
        }
        if (empty($this->arguments['title']) && $title) {
            $this->tag->addAttribute('title', $title);
        }

        return $this->tag->render() . ($noscriptTag ?? '');
    }

    /**
     * @param FileInterface $image
     * @return bool
     */
    protected function isImageRegenerationRequired(FileInterface $image): bool
    {
        if (method_exists(
            $image,
            'getExtension'
        ) && isset($this->settings['viewHelpers']['responsiveImage']['dontRegenerateFileFormats'])) {
            $fileExt = $image->getExtension();
            $excluded = GeneralUtility::trimExplode(
                ',',
                $this->settings['viewHelpers']['responsiveImage']['dontRegenerateFileFormats']
            );

            return !in_array($fileExt, $excluded);
        }

        return true;
    }
}
