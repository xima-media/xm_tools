<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Register own SoftRefParser for links in FlexForm-Fields
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['softRefParser']['tx_xmtools_flexform_typolink_tag'] = 'Xima\\XmTools\\Database\\SoftReferenceIndex';
