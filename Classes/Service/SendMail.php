<?php

namespace Xima\XmTools\Service;

use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class SendMail
 *
 * @author Steve Lenz <kontakt@steve-lenz.de>
 * @version 1.1.0
 *
 * https://docs.typo3.org/typo3cms/CoreApiReference/ApiOverview/Mail/Index.html
 */
class SendMail
{
    /**
     * @var string
     */
    private string $mailCharset = 'utf-8';

    /**
     * Path to attachment
     *
     * @var string
     */
    private string $attachment = '';

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
     * @return bool TRUE on success, otherwise false
     */
    public function sendTemplateEmail(
        array $recipient,
        array $sender,
        string $subject,
        string $template,
        array $variables = [],
        array $cc = [],
        array $bcc = [],
        array $layoutRootPaths = [],
        array $partialRootPaths = [],
        string $format = 'html'
    ): bool {
        /** @var StandaloneView $emailView */
        $emailView = GeneralUtility::makeInstance(StandaloneView::class);
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
            ->html($emailBody, $this->mailCharset);

        if ($this->attachment !== '') {
            $message->attachFromPath($this->attachment);
            $this->attachment = '';
        }

        $message->send();

        return $message->isSent();
    }

    /**
     * Sets path to attachment
     *
     * @param string $attachment
     */
    public function setAttachment(string $attachment): void
    {
        $this->attachment = $attachment;
    }

    /**
     * @param string $mailCharset Default: utf-8
     */
    public function setMailCharset(string $mailCharset): void
    {
        $this->mailCharset = $mailCharset;
    }

    /**
     * @param array $addresses Format: array('mail1;name1')
     * @param string $delimiter
     * @return array
     */
    public function getExplodedEmailAddresses(array $addresses, string $delimiter = ';'): array
    {
        $result = [];

        foreach ($addresses as $item) {
            [$address, $name] = explode($delimiter, $item);
            $result[$address] = $name;
        }

        return $result;
    }
}
