<?php

namespace Xima\XmTools\Service;

use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class SendMail
 *
 * @author Steve Lenz <kontakt@steve-lenz.de>
 * @package TYPO3 > 6.2.x
 * @version 1.1.0
 *
 * https://docs.typo3.org/typo3cms/CoreApiReference/ApiOverview/Mail/Index.html
 */
class SendMail
{
    /**
     * @var ObjectManager
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
     * Path to attachment
     *
     * @var string
     */
    private $attachment = '';

    /**
     * @var array
     */
    private $replyTo = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
    }

    /**
     * Sends an e-amil with fluid template
     *
     * @param array $recipient recipient of the email in the format array('recipient@domain.tld' => 'Recipient Name')
     * @param array $sender sender of the email in the format array('sender@domain.tld' => 'Sender Name')
     * @param string $subject subject of the email
     * @param string $template absolute template path
     * @param array $variables variables to be passed to the Fluid view
     * @param array $cc cc of the email in the format array('recipient@domain.tld' => 'Recipient Name')
     * @param array $bcc bcc of the email in the format array('recipient@domain.tld' => 'Recipient Name')
     * @param array $layoutRootPaths
     * @param array $partialRootPaths
     * @param string $format The desired format, something like "html", "xml", "png", "json" or the like. Can even be something like "rss.xml".
     * @return boolean TRUE on success, otherwise false
     */
    public function sendTemplateEmail(
        array $recipient,
        array $sender,
        $subject,
        $template,
        array $variables = [],
        array $cc = [],
        array $bcc = [],
        array $layoutRootPaths = [],
        array $partialRootPaths = [],
        $format = 'html'
    ) {
        /** @var StandaloneView $emailView */
        $emailView = $this->objectManager->get(StandaloneView::class);
        $emailView->setFormat($format);
        $emailView->setTemplatePathAndFilename($template);
        $emailView->setLayoutRootPaths($layoutRootPaths);
        $emailView->setPartialRootPaths($partialRootPaths);
        $emailView->assignMultiple($variables);
        $emailBody = $emailView->render();

        /** @var MailMessage $message */
        $message = GeneralUtility::makeInstance(MailMessage::class);
        $message->setTo($recipient)
            ->setFrom($sender)
            ->setCc($cc)
            ->setBcc($bcc)
            ->setSubject($subject)
            ->setBody($emailBody, $this->mailContentType, $this->mailCharset);

        if (!empty($this->replyTo)) {
            foreach ($this->replyTo as $address => $name) {
                $message->addReplyTo($address, $name);
            }
        }

        if ($this->attachment != '') {
            $message->attach(\Swift_Attachment::fromPath($this->attachment));
            $this->attachment = '';
        }

        $message->send();

        return $message->isSent();
    }

    /**
     * @param string $address
     * @param string $name    optional
     *
     * @return $this
     */
    public function addReplyTo($address, $name = null)
    {
        $this->replyTo[$address] = $name;

        return $this;
    }

    /**
     * Sets path to attachment
     *
     * @param string $attachment
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
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
