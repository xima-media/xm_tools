<?php

/**
 * Add ToolbarItems
 * From v12.0 this has no effect here
 * @link https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96041-ToolbarItemsRegisterByTag.html
 */
$GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1571038828] = \Xima\XmTools\Backend\ToolbarItems\WebsiteVersionToolbarItem::class;

// Register "xmt" as global fluid namespace
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['xmt'] = ['Xima\\XmTools\\ViewHelpers'];
