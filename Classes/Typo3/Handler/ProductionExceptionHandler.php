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
class ProductionExceptionHandler extends \TYPO3\CMS\Core\Error\ProductionExceptionHandler
{

    /**
     * Displays the given exception
     *
     * @param \Exception $exception The exception object
     * @return void
     */
    public function handleException(\Exception $exception)
    {
        $this->sendNotificationMail($exception);
        parent::handleException($exception);
    }

    /**
     * Sends an exception as notification e-mail
     *
     * @param $exception
     */
    protected function sendNotificationMail($exception)
    {
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');
        $settings = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);


        if (!isset($settings['xmTools.']['errorHandler.']['recipient'])) {
            exit;
        }

        $subject = 'Exception: ' . $_SERVER['SERVER_NAME'];

        $message = 'Title: ' . $this->getTitle($exception) . PHP_EOL . PHP_EOL;
        $message .= 'Message: ' . PHP_EOL . $this->getMessage($exception) . PHP_EOL . PHP_EOL;
        $message .= 'Page: ' . PHP_EOL . 'http://' . $_SERVER['SERVER_NAME'] .$_SERVER['REQUEST_URI'] . PHP_EOL . PHP_EOL;
        $message .= 'Request-Info: ' . PHP_EOL . print_r($_SERVER, true) . PHP_EOL . PHP_EOL;

        mail($settings['xmTools.']['errorHandler.']['recipient'], $subject, $message);
    }

}