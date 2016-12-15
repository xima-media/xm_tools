<?php
namespace Xima\XmTools\DataProcessing;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Class for data processing for the content element "My new content element"
 */
class ContentElementFlexformProcessor implements DataProcessorInterface
{

    /**
     * Process data for the content element "My new content element"
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $processedData['flexform'] = $this->getOptionsFromFlexFormData($processedData['data']);

        return $processedData;
    }

    /**
     * @param array $row
     * @return array
     */
    protected function getOptionsFromFlexFormData(array $row)
    {
        $options = [];
        $flexFormAsArray = GeneralUtility::xml2array($row['pi_flexform']);
        if (!empty($flexFormAsArray['data']['sDEF']['lDEF']) && is_array($flexFormAsArray['data']['sDEF']['lDEF'])) {
            foreach ($flexFormAsArray['data']['sDEF']['lDEF'] as $optionKey => $optionValue) {
                $optionParts = explode('.', $optionKey);
                $options[array_pop($optionParts)] = $optionValue['vDEF'] === '1' ? true : $optionValue['vDEF'];
            }
        }
        return $options;
    }
}
