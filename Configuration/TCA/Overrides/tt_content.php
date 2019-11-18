<?php

use TYPO3\CMS\Core\Utility\ArrayUtility;

defined('TYPO3_MODE') or die();

/**
 * Example of configuring the flexform field in tt_content for soft reference parsing typolink tags
 * Add this to the Configuration/TCA/Overrides/tt_content.php file of your extension to enable this feature:
 */
//ArrayUtility::mergeRecursiveWithOverrule($GLOBALS['TCA']['tt_content']['columns']['pi_flexform']['config'], [
//    'softref' => 'tx_xmtools_flexform_typolink_tag',
//]);
