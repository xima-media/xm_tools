<?php
/**
 * Created by PhpStorm.
 * User: markus
 * Date: 21.12.16
 * Time: 12:21
 */

namespace Xima\XmTools\Extensionmanager;

use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FlexFormUtility
{
    /*
    * Extend existing FlexForm definition
    *
    * @param $base base-Extension-Name
    * @param $extension Extension-Name
    * @param $original filename of flexform
    * @param $addition filename of additional flexform
    *
    */
    public function mergeFlexFormDefinitions($base, $extension, $original, $addition)
    {
        $old = GeneralUtility::getUrl(
            ExtensionManagementUtility::extPath($base, 'Configuration/FlexForms/' . $original)
        );
        $currentValueArray = GeneralUtility::xml2array($old);

        $add = GeneralUtility::getUrl(
            ExtensionManagementUtility::extPath($extension, 'Configuration/FlexForms/' . $addition)
        );
        $additionalValueArray = GeneralUtility::xml2array($add);

        $newValueArray = $currentValueArray;

        foreach ($currentValueArray['sheets'] as $sheetTitle => $sheet) {
            if (is_array($additionalValueArray)
                && is_array($additionalValueArray['sheets'])
                && isset($additionalValueArray['sheets'][$sheetTitle])) {
                $newValueArray['sheets'][$sheetTitle]['ROOT']['el'] = array_merge(
                    $currentValueArray['sheets'][$sheetTitle]['ROOT']['el'],
                    $additionalValueArray['sheets'][$sheetTitle]['ROOT']['el']
                );
            }
        }

        $flexTools = GeneralUtility::makeInstance(FlexFormTools::class);
        $new = $flexTools->flexArray2Xml($newValueArray, true);

        return $new;
    }
}
