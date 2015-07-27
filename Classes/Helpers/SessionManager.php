<?php
namespace TxXmTools\Classes\Helpers;

class SessionManager {

    var $sessions = array();
    
    /**
     * Write data into session
     *
     * @param mixed $data
     */
    public function set($key, $value, $postfix = '') {

        $session = $this->getSession($postfix);
        
        return $session->set($key, $value);
    }

    /**
	 * Restore data from session
	 *
	 * @param string $key        	
	 * @return mixed
	 */
    public function get($key, $postfix = '') {

        $session = $this->getSession($postfix);
        
        return $session->get($key);
    }

    /**
	 * Clean up session
	 */
    public function cleanUp() {

        $session = $this->getSession($postfix);
        
        return $session->cleanUp();
    }
}