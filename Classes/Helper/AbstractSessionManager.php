<?php

namespace Xima\XmTools\Classes\Helper;

/**
 * Abstract class for managing multiple session stores.
 * Use get or set to retrieve or store values and an optional postfix to select an session object.
 *
 * @author Wolfram Eberius <woe@xima.de>
 */
abstract class AbstractSessionManager
{
    protected $sessionStores = array();

    abstract protected function getSession($postfix);

    public function getSessionStores()
    {
        return $this->sessionStores;
    }

    public function setSessionStores($sessionStores)
    {
        $this->sessionStores = $sessionStores;
    }
}
