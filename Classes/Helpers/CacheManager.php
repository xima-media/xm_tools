<?php
namespace TxXmTools\Classes\Helpers;

/**
 * Helferklasse für Typo3-Extensions mit Extbase
 * 
 * @author Wolfram Eberius <woe@xima.de>
 * @version 1.0.0
 */
class CacheManager  implements \t3lib_Singleton{

	protected $path = 'Data/cache/';
	
	/**
	 *
	 * @var TxXmTools\Classes\Helpers\Typo3Services
	 * @inject
	 */
	protected $typo3services;
	
	public function get($filename)
	{
		$filename = $this->getFullPath($filename);
		
		if (file_exists($filename) && $this->isFileValid($filename))
		{
			return file_get_contents($filename);
		}
		
		return false;
	}
	
	public function write($filename, $content)
	{
		$filename = $this->getFullPath($filename);
		
		return file_put_contents ($filename, $content);
	}
    
	private function getFullPath($filename)
	{
		$filename = preg_replace('/[^a-zA-Z0-9]+/', '_', $filename);
		$filename = md5($filename);
		
		$filename = getcwd().'/'.$this->typo3services->getRelPath().$this->path.$filename.'.json';

		return $filename;
	}
		
	private function isFileValid($filename)
	{
		return ( date ('F d Y', strtotime('today')) == date ('F d Y', filemtime($filename)) );
	}
	
	public function clear() {

	    $extensionKey = $_GET['extension_key'];
	    if (strpos($extensionKey, 'xm_') === 0)
	    {
    	    return $this->clearCache($extensionKey);
	    }

	    die('Extension key '.$extensionKey.' not supported by Xima Tools.');
	}
	
	private function clearCache($extensionKey){

	    $path = \t3lib_extMgm::extRelPath($extensionKey).$this->path.'*';
	    if(strpos($path,'typo3conf') !== false) $path = '../'.$path;

	    $files = glob($path, GLOB_BRACE); // get all file names
	    foreach($files as $file){ // iterate files
	        if(is_file($file))
	            unlink($file); // delete file
	    }
	    
	    return true;
	    
	}
	
	
}
