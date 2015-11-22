<?php

//register the autoloader for xm_tools classes
spl_autoload_register(function ($class) {
    $debug = false;
    if ($debug) {
        echo 'Trying to autoload \''.$class.'\'...';
    }

    $classFile = null;

    //only namespaces class names, not extbase
    if (strstr($class, 'Xima\XmTools')) {
        $classFile = str_replace('Xima\XmTools', 'xm_tools', $class);
        $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $classFile);
        $classPI = pathinfo($classFile);

        if (strstr(PATH_site, 'xm_tools/')) {
            // in case we are about to run tests we have to remove one 'xm_tools'
            $replaceTimes = 1;
            $classPI['dirname'] = str_replace('xm_tools/', '', $classPI['dirname'], $replaceTimes);
            $file = PATH_site;
        } else {
            // in case we come from the cms
            $file = PATH_site.'typo3conf'.DIRECTORY_SEPARATOR.'ext'.DIRECTORY_SEPARATOR;
        }
        $file .= $classPI['dirname'].DIRECTORY_SEPARATOR.$classPI['filename'].'.php';

        if ($debug) {
            echo 'Searching for file \''.$file.'\'...';
        }

        if (is_readable($file)) {
            if ($debug) {
                echo 'Ok!<br/>';
            }

            require_once $file;
        } else {
            if ($debug) {
                echo 'Fail.<br/>';
            }
        }
    } else {
        if ($debug) {
            echo 'Class is not part of xm_tools.<br/>';
        }
    }
});

$autoLoaderVendors = __DIR__.'/vendor/autoload.php';
if (is_readable($autoLoaderVendors)) {
    require_once $autoLoaderVendors;
}