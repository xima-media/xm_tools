<?php

// respect if coming from a TYPO3 installation or e.g. phpunit test
try {
    define('PATH_XM_TOOLS', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('xm_tools'));
} catch (\BadFunctionCallException $e) {
    define('PATH_XM_TOOLS', __DIR__ . DIRECTORY_SEPARATOR);
}

//register the autoloader for xm_tools classes
spl_autoload_register(function ($class) {
    $classFile = null;
    //only namespaces class names, not extbase
    if (strstr($class, 'Xima\XmTools')) {
        $classFile = str_replace('Xima\XmTools', 'xm_tools', $class);
        $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $classFile);

        $classPI = pathinfo($classFile);
        $classPI['dirname'] = str_replace('xm_tools' . DIRECTORY_SEPARATOR, '', $classPI['dirname']);

        $file = PATH_XM_TOOLS . $classPI['dirname'] . DIRECTORY_SEPARATOR . $classPI['filename'] . '.php';

        require_once $file;
    }
});

//register the autoloader for vendors
$autoloadFile = PATH_XM_TOOLS . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once $autoloadFile;
