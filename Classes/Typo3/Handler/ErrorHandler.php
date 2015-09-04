<?php

namespace Xima\XmTools\Typo3\Handler;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * ErrorHandler
 *
 * @author Steve Lenz <steve.lenz@xima.de>
 * @copyright (c) 2015, XIMA Media GmbH
 * @version 1.0.0
 */
class ErrorHandler extends \TYPO3\CMS\Core\Error\ErrorHandler
{

    /**
     * TypoScript settings ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
     * @var array
     */
    protected $settings = null;

    /**
     *
     */
    protected function initialize()
    {
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');
        $this->settings = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
    }

    /**
     * Registers this class as default error handler
     *
     * @param int $errorHandlerErrors The integer representing the E_* error level which should be
     */
    public function __construct($errorHandlerErrors)
    {
        $excludedErrors = E_COMPILE_WARNING | E_COMPILE_ERROR | E_CORE_WARNING | E_CORE_ERROR | E_PARSE | E_ERROR;
        // reduces error types to those a custom error handler can process
        $errorHandlerErrors = $errorHandlerErrors & ~$excludedErrors;
        set_error_handler(array($this, 'handleError'), $errorHandlerErrors);
        register_shutdown_function(array($this, 'shutdown'));
    }

    /**
     * @inheritdoc
     */
    public function handleError($errorLevel, $errorMessage, $errorFile, $errorLine)
    {
        if (E_ERROR == $errorLevel) {
            $this->sendNotificationMail($errorLevel, $errorMessage, $errorFile, $errorLine);
        }

        parent::handleError($errorLevel, $errorMessage, $errorFile, $errorLine);
    }

    /**
     *
     */
    public function shutdown()
    {
        $error = error_get_last();

        if (E_ERROR == $error['type']) {
            $this->sendNotificationMail($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    /**
     * @param int $errorLevel
     * @param string $errorMessage
     * @param string $errorFile
     * @param int $errorLine
     */
    protected function sendNotificationMail($errorLevel, $errorMessage, $errorFile, $errorLine)
    {
        $this->initialize();

        if (!isset($this->settings['xmTools.']['errorHandler.']['recipient'])) {
            exit;
        }

        $errorLevelName = $this->getErrorType($errorLevel);
        $subject = $errorLevelName . ': ' . $_SERVER['SERVER_NAME'];

        $message = 'Server: ' . $_SERVER['SERVER_NAME'] . PHP_EOL . PHP_EOL;
        $message .= 'Level: ' . $errorLevelName . PHP_EOL . PHP_EOL;
        $message .= 'Message: ' . $errorMessage . PHP_EOL . PHP_EOL;
        $message .= 'File: ' . $errorFile . PHP_EOL . PHP_EOL;
        $message .= 'Line: ' . $errorLine;

        mail($this->settings['xmTools.']['errorHandler.']['recipient'], $subject, $message);
    }

    /**
     * @param $errorLevel
     * @return string|false
     */
    protected function getErrorType($errorLevel)
    {
        $errorTypes = array(
            E_ERROR             => 'ERROR',
            E_WARNING           => 'WARNING',
            E_PARSE             => 'PARSING ERROR',
            E_NOTICE            => 'NOTICE',
            E_CORE_ERROR        => 'CORE ERROR',
            E_CORE_WARNING      => 'CORE WARNING',
            E_COMPILE_ERROR     => 'COMPILE ERROR',
            E_COMPILE_WARNING   => 'COMPILE WARNING',
            E_USER_ERROR        => 'USER ERROR',
            E_USER_WARNING      => 'USER WARNING',
            E_USER_NOTICE       => 'USER NOTICE',
            E_STRICT            => 'STRICT NOTICE',
            E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR'
        );

        return isset($errorTypes[$errorLevel]) ? $errorTypes[$errorLevel] : 'UNKNOWN ERROR';
    }

}
