<?php
namespace TxXmTools\Classes\Helpers;

use TxXmTools\Classes\Helpers\SessionManager;

class Typo3SessionManager extends SessionManager {

    /**
     *
     * @var \Tx_Extbase_Object_ObjectManagerInterface 
     * @inject
     */
    protected $objectManager;
    
    protected function getSession($postfix){
        
        if (!isset($this->sessions[$postfix]))
        {
            $session = $this->objectManager->get ('TxXmTools\Classes\Helpers\Typo3Session');

            $session->switchKey ($postfix);

            $this->sessions[$postfix] = $session;
        }
            
        return $this->sessions[$postfix];
    }
}