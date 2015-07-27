<?php
namespace TxXmTools\Classes\Helpers;

use TxXmTools\Classes\Helpers\Helper;

/**
 * The Api facade
 *
 * @author Wolfram Eberius <woe@xima.de>
 * @version 1.0.0
 */
class RESTApiHelper {
    protected $url;
    protected $cacheUrl;
    protected $apiKey;
    protected $apiUrl;
    protected $apiSchema;
    protected $isApiCacheEnabled;
    protected $fallbackLanguage;
    
    /**
     *
     * @var TxXmTools\Classes\Helpers\CacheManager
     * @inject
     */
    protected $cacheManager;
    
    /**
     *
     * @var TxXmTools\Classes\Helpers\Typo3Services
     * @inject
     */
    protected $typo3Services;

    public function initializeObject() {

        $settings = $this->typo3Services->getSettings ();

        $this->setApiKey ($settings ['apiKey']);
        $this->setApiUrl ($settings ['apiUrl']);
        $this->setApiSchema($settings ['apiSchema']);
        $this->setIsApiCacheEnabled ($settings ['isApiCacheEnabled']);
        $this->setFallbackLanguage ($settings ['fallbackLanguage']);
    }

    public function get($route, $repository, $params = array()) {

        $repositoryClassName = get_class ($repository);
        $params ['lang'] = $this->typo3Services->getIsoLang ();
        
        $modelClassName = str_replace ('Repository_', 'Model_', $repositoryClassName);
        $modelClassName = str_replace ('Repository', '', $modelClassName);
        
        $this->buildUrl ($route, $params);
        $response = false;
        
        // retrieve data from cache or api
        if ($this->getIsApiCacheEnabled ()) {
            
            $response = $this->cacheManager->get ($this->cacheUrl);
        }
        if (! $response) {
            $response = file_get_contents ($this->url);
            if ($response && $this->getIsApiCacheEnabled ()) {
                $this->cacheManager->write ($this->cacheUrl, $response);
            }
        }
        
        $response = json_decode ($response, true);
        $targetLanguage = (isset($response ['metadata']['lang']))? ($response ['metadata']['lang']) : $this->typo3Services->getLang ();
        $response ['result'] = Helper::translate ($response ['result'], $targetLanguage, $this->getFallbackLanguage ());
        
        // if it is a single result and a single result was queried we still want to return an array of arrays
        if (! is_int (array_shift (array_keys ($response ['result']))) && isset ($response ['result'] ['id'])) {
            $response ['result'] = array (
                $id => $response ['result'] );
        }
        
        if (class_exists ($modelClassName)) {
            $result = array ();
            foreach ( $response ['result'] as $id => $data ) {
                
                $result [$id] = new $modelClassName ();
                
                // make the entities fit typo3 better
                $data ['uid'] = $id;
                
                foreach ( $data as $key => $value ) {
                    $setter = 'set' . Helper::underscoreToCamelCase($key);
                    if (method_exists ($result [$id], $setter)) {
                        $result [$id]->$setter ($value);
                    }
                    else {
                        $result [$id]->{$key} = $value;
                    }
                }
            }
            
            $response ['result'] = $result;
        }
        
        return $response;
    }

    /**
     * 
     * @param string $route
     * @param Array $params
     * 
     * the api schema should be set in the constant editor to something like:
     * [Api-URL]/[Api-Key][Api-Route]
     * [Api-URL][Api-Route]?[Api-Key]
     * 
     * @return string
     */
    protected function buildUrl($route, $params) {
        
        // handle params
        $paramsAsString = array ();
        $queryString = '';
        foreach ( $params as $key => $value ) {
            $paramsAsString [] = $key . '=' . urlencode ($value);
        }
        if (! empty ($paramsAsString)) {
            $queryString = implode ('&', $paramsAsString);
        }
        
        $placeHolders = array ('[Api-URL]', '[Api-Route]', '[Api-Key]');
        $replace = array ($this->apiUrl, $route, $this->apiKey);
        $url = str_replace($placeHolders, $replace, $this->apiSchema);
        
        $url .= (strstr ($url, '?')) ? '&' : '?';
        $url .= $queryString;
      
        //echo $url;echo '<br>';
        
        $this->url = $url;
        $this->cacheUrl = $route . '_' . $queryString;
        
        return $url;
    }

    public function getUrl() {

        return $this->url;
    }

    public function setUrl($url) {

        $this->url = $url;
    }

    public function getApiKey() {

        return $this->apiKey;
    }

    public function setApiKey($apiKey) {

        $this->apiKey = $apiKey;
    }

    public function getApiUrl() {

        return $this->apiUrl;
    }

    public function setApiUrl($apiUrl) {

        $this->apiUrl = $apiUrl;
    }
    
    public function getApiSchema() {
    
        return $this->apiSchema;
    }
    
    public function setApiSchema($apiSchema) {
    
        $this->apiSchema = $apiSchema;
    }

    public function getIsApiCacheEnabled() {

        return $this->isApiCacheEnabled;
    }

    public function setIsApiCacheEnabled($isApiCacheEnabled) {

        $this->isApiCacheEnabled = $isApiCacheEnabled;
    }

    public function getFallbackLanguage() {

        return $this->fallbackLanguage;
    }

    public function setFallbackLanguage($fallbackLanguage) {

        $this->fallbackLanguage = $fallbackLanguage;
    }
}