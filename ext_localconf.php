<?php
spl_autoload_register (function ($class) {
    $debug = false;
    if ($debug) echo '<h1>xm_tools</h1>';
    if ($debug) echo $class . '...';
    if (strstr($class, 'TxXmTools') || strstr($class, 'Tx_XmTools'))
    {
        $classFile = str_replace ('TxXmTools', 'xm_tools', $class);
        $classFile = str_replace ('\\', DIRECTORY_SEPARATOR, $classFile);
        $classPI = pathinfo ($classFile);

        $file = __DIR__ . DIRECTORY_SEPARATOR . 'ext' . DIRECTORY_SEPARATOR . $classPI ['dirname'] . DIRECTORY_SEPARATOR . $classPI ['filename'] . '.php';
        if ($debug) echo $file .'...';

        if (file_exists($file)){
           if ($debug)  echo 'Ok!<br/>';
            require_once ($file);
        }
        else {
            if ($debug)  echo 'Fail.<br/>';
        }
    }
    else {
        if ($debug) echo 'Not in this extension.<br/>';
    }
});

$TYPO3_CONF_VARS['BE']['AJAX']['xm_tools::clearCache'] = 'EXT:xm_tools/Classes/Helpers/CacheManager.php:TxXmTools\Classes\Helpers\CacheManager->clear';