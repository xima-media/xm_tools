<?php

namespace Xima\XmTools\Classes\Typo3;

use Xima\XmTools\Classes\Helper\AbstractSessionManager;

/**
 * TYPO3 session manager. Managages the retrieval of selected TYPO3 session stores.
 *
 * @author Wolfram Eberius <woe@xima.de>
 */
class SessionManager extends AbstractSessionManager implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * @var \Xima\XmTools\Classes\Typo3\Services
     * @inject
     */
    protected $typo3Services;

    /**
     * Write data into session.
     *
     * @param string                                      $key
     * @param mixed                                       $value
     * @param string                                      $postfix
     * @param \Xima\XmTools\Classes\Typo3\Model\Extension $extension store session data for another extension
     */
    public function set($key, $value, $postfix = null, \Xima\XmTools\Classes\Typo3\Model\Extension $extension = null)
    {
        $session = $this->getSession($postfix, $extension);

        return $session->set($key, $value);
    }

    /**
     * Restore data from session.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key, $postfix = null, $extension = null)
    {
        $session = $this->getSession($postfix, $extension);

        return $session->get($key);
    }

    /**
     * Clean up session.
     */
    public function cleanUp($postfix = null, $extension = null)
    {
        $session = $this->getSession($postfix, $extension);

        return $session->cleanUp();
    }

    protected function getSession($postfix = null, $extension = null)
    {
        $postfix = (is_null($postfix)) ? $GLOBALS ['TSFE']->id : $postfix;
        $extension = (is_null($extension)) ? $this->typo3Services->getExtension() : $extension;

        //put the session key together: extension key first, then current page id or custom postfix
        $sessionKey = $extension->getKey().$postfix;

        if (!isset($this->sessionStores[$sessionKey])) {
            $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
            $sessionStore = $objectManager->get('Xima\XmTools\Classes\Typo3\SessionStore');
            /* @var $sessionStore \Xima\XmTools\Classes\Typo3\SessionStore */
            $sessionStore->setSessionKey($sessionKey);

            $this->sessionStores[$sessionKey] = $sessionStore;
        }

        return $this->sessionStores[$sessionKey];
    }
}
