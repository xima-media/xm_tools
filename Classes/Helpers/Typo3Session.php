<?php
namespace TxXmTools\Classes\Helpers;

/**
 * Session-Handler for TYPO3 Extbase
 * 
 * @author Steve Weinert <kontakt@steve-weinert.de>
 * @copyright (c) 2012, Steve Weinert
 * @version 1.0.0
 */
class Typo3Session {
	protected $data = array ();
	protected $primaryKey = 'ses';
	protected $secondaryKey = '';

	/**
	 *
	 * @var TxXmTools\Classes\Helpers\Typo3Services 
	 * @inject
	 */
	protected $typo3services;
	public function initializeObject() {
		$this->secondaryKey = ($this->secondaryKey == '')? $this->typo3services->getExtensionKey () . $GLOBALS ['TSFE']->id : $this->typo3services->getExtensionKey () . $this->secondaryKey;
		$this->data = unserialize ( $GLOBALS ['TSFE']->fe_user->getKey ( $this->primaryKey, $this->secondaryKey ) );
	}
	
	/**
	 * Write data into session
	 *
	 * @param mixed $data        	
	 */
	public function set($key, $value) {
		$this->data [$key] = $value;
		$sessionData = serialize ( $this->data );
		$GLOBALS ['TSFE']->fe_user->setKey ( $this->primaryKey, $this->secondaryKey, $sessionData );
		return ($GLOBALS ['TSFE']->fe_user->storeSessionData ());
	}
	
	/**
	 * Restore data from session
	 *
	 * @param string $key        	
	 * @return mixed
	 */
	public function get($key) {
		return array_key_exists ( $key, $this->data ) ? $this->data [$key] : null;
	}
	
	/**
	 * Clean up session
	 */
	public function cleanUp() {
		$GLOBALS ['TSFE']->fe_user->setKey ( $this->primaryKey, $this->secondaryKey, NULL );
		$GLOBALS ['TSFE']->fe_user->storeSessionData ();
		
		return true;
	}
	
	/**
	 * Switch session key
	 */
	public function switchKey($key) {
	    
		$this->secondaryKey = $key;
		$this->initializeObject ();
		
		return true;
	}
}