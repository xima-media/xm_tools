<?php
namespace Xima\XmTools\Classes\Typo3;

use Xima\XmTools\Classes\Helper\AbstractSessionStore;

/**
 * Session store for TYPO3 Extbase.
 *
 * @package xm_tools
 * @author Steve Lenz <sle@xima.de>
 * @author Wolfram Eberius <woe@xima.de>
 */
class SessionStore extends AbstractSessionStore
{
    const PRIMARY_KEY = 'ses';

    protected $sessionKey = null;

    /* (non-PHPdoc)
     * @see \Xima\XmTools\Classes\Helper\AbstractSessionStore::set()
     */
    public function set($key, $value)
    {
        $result = false;

        if (!is_null($this->sessionKey)) {
            $this->data [$key] = $value;
            $sessionData = serialize($this->data);
            $GLOBALS ['TSFE']->fe_user->setKey(SessionStore::PRIMARY_KEY, $this->sessionKey, $sessionData);

            $result = $GLOBALS ['TSFE']->fe_user->storeSessionData();
        } else {
            throw new \Exception('Warning: no $sessionKey set in '.__CLASS__.'. Please use Xima\XmTools\Classes\Typo3\SessionManager over Xima\XmTools\Classes\Typo3\SessionStore.');
        }

        return $result;
    }

    /* (non-PHPdoc)
     * @see \Xima\XmTools\Classes\Helper\AbstractSessionStore::get()
     */
    public function get($key)
    {
        $result = false;

        if (!is_null($this->sessionKey)) {
            $result = array_key_exists($key, $this->data) ? $this->data [$key] : null;
        } else {
            throw new \Exception('Warning: no $sessionKey set in '.__CLASS__.'. Please use Xima\XmTools\Classes\Typo3\SessionManager over Xima\XmTools\Classes\Typo3\SessionStore.');
        }

        return $result;
    }

    /* (non-PHPdoc)
     * @see \Xima\XmTools\Classes\Helper\AbstractSessionStore::cleanUp()
     */
    public function cleanUp()
    {
        $result = false;

        if (!is_null($this->sessionKey)) {
            $GLOBALS ['TSFE']->fe_user->setKey(SessionStore::PRIMARY_KEY, $this->sessionKey, null);

            $result = $GLOBALS ['TSFE']->fe_user->storeSessionData();
        }

        return $result;
    }

    public function getSessionKey()
    {
        return $this->sessionKey;
    }

    /**
     * Sets the session key and retrieves data if there is some for this session key. Essential to be called before using.
     *
     * @param  string                                   $sessionKey
     * @return \Xima\XmTools\Classes\Typo3\SessionStore
     */
    public function setSessionKey($sessionKey)
    {
        $this->sessionKey = $sessionKey;

        //retrieve data from session
        $data = unserialize($GLOBALS ['TSFE']->fe_user->getKey(SessionStore::PRIMARY_KEY, $this->sessionKey));
        if (is_array($data)) {
            $this->data = $data;
        }

        return $this;
    }
}
