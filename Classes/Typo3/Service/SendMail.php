<?php
namespace Xima\XmTools\Classes\Typo3\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SendMail
 * @author Steve Lenz <kontakt@steve-lenz.de>
 * @package TYPO3 6.2.x
 * @version 1.1.0
 *
 * https://docs.typo3.org/typo3cms/CoreApiReference/ApiOverview/Mail/Index.html
 */
class SendMail
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    private $objectManager = null;

    /**
     * @var string
     */
    private $mailContentType = 'text/plain';

    /**
     * @var string
     */
    private $mailCharset = 'utf-8';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
    }

    /**
     * Sends an e-amil with fluid template
     * @param array $recipient recipient of the email in the format array('recipient@domain.tld' => 'Recipient Name')
     * @param array $sender sender of the email in the format array('sender@domain.tld' => 'Sender Name')
     * @param string $subject subject of the email
     * @param string $template absolute template path
     * @param array $variables variables to be passed to the Fluid view
     * @param array $cc cc of the email in the format array('recipient@domain.tld' => 'Recipient Name')
     * @param array $bcc bcc of the email in the format array('recipient@domain.tld' => 'Recipient Name')
     * @return boolean TRUE on success, otherwise false
     */
    public function sendTemplateEmail(array $recipient, array $sender, $subject, $template, array $variables = array(), array $cc = array(), array $bcc = array())
    {
        $emailView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
        $emailView->setFormat('html');
        $emailView->setTemplatePathAndFilename($template);
        $emailView->assignMultiple($variables);
        $emailBody = $emailView->render();

        $message = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
        $message->setTo($recipient)
            ->setFrom($sender)
            ->setCc($cc)
            ->setBcc($bcc)
            ->setSubject($subject)
            ->setBody($emailBody, $this->mailContentType, $this->mailCharset)
            ->send();

        return $message->isSent();
    }

    /**
     * @param string $mailContentType Content type like text/plain | text/html | multipart/related; Default: text/plain
     */
    public function setMailContentType($mailContentType)
    {
        $this->mailContentType = $mailContentType;
    }

    /**
     * @param string $mailCharset Default: utf-8
     */
    public function setMailCharset($mailCharset)
    {
        $this->mailCharset = $mailCharset;
    }

    /**
     * @param array $addresses Format: array('mail1;name1')
     * @param string $delimiter
     * @return array
     */
    public function getExplodedEmailAddresses(array $addresses, $delimiter = ';')
    {
        $result = array();

        foreach ($addresses as $item) {
            list($address, $name) = explode($delimiter, $item);
            $result[$address] = $name;
        }

        return $result;
    }
}