<?php

/**
 * Add ToolbarItems
 */
$GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1571038828] = \Xima\XmTools\Backend\ToolbarItems\WebsiteVersionToolbarItem::class;

// Register "xmt" as global fluid namespace
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['xmt'] = ['Xima\\XmTools\\ViewHelpers'];
