<?php
namespace Xima\XmTools\Classes\API\REST\Repository;

use Xima\XmTools\Classes\Typo3\Extension\ExtensionAwareInterface;
use Xima\XmTools\Classes\Helper\Helper;
/**
 * Abstract class for extbase repositories to retrieve data through a REST URL.
 * The concrete repository must provide the API route relative to the API URL configured in
 * the TYPO3 constant editor for the concrete extension.
 *
 * @package xm_tools
 * @author Wolfram Eberius <woe@xima.de>
 *
 */
class ApiRepository extends \Xima\XmTools\Classes\Typo3\Domain\Repository\Repository
{

    const PLACEHOLDER_API_URL = '[Api-URL]';
    const PLACEHOLDER_API_ROUTE = '[Api-Route]';
    const PLACEHOLDER_API_KEY = '[Api-Key]';
    const PLACEHOLDER_TARGET = '[Target]';

    
    /**
     * @var \Xima\XmTools\Classes\Typo3\Extension\ExtensionManager
     * @inject
     */
    protected $extensionManager;

    /**
     * connector
     *
     * @var \Xima\XmTools\Classes\API\REST\Connector
     * @inject
     */
    protected $connector;
    
    /**
     *
     * @var \Xima\XmTools\Classes\Typo3\Services
     * @inject
     */
    protected $typo3Services;

    protected $apiKey;
    protected $apiUrl;
    protected $apiSchema;
    protected $apiRouteFindById;
    protected $apiRouteFindByQuery;
    
    protected $lastReponse = array();

    public function initializeObject()
    {
        $extensionName = null;
        if ($this instanceof \Xima\XmTools\Classes\Typo3\Extension\ExtensionAwareInterface)
        {
            $extensionName = Helper::getClassPackageName($this);
        }

        $extension = $this->extensionManager->getExtensionByName($extensionName);
        $settings = $extension->getSettings();

        $this->setApiKey($settings ['api']['key']);
        $this->setApiUrl($settings ['api']['url']);
        $this->setApiSchema($settings ['api']['schema']);
        $this->setApiRouteFindById($settings ['api']['routeFindById']);
        $this->setApiRouteFindByQuery($settings ['api']['routeFindByQuery']);
        
        $this->connector->setExtension($extension);

    }

    /**
     * find
     *
     * @param $id
     * @return The model object with the given id.
     */
    public function findByUid($id)
    {
        $target = $this->getApiTarget();
        $apiRoute = str_replace(ApiRepository::PLACEHOLDER_TARGET, $target, $this->apiRouteFindById).'/'.$id;
        $apiUrl = $this->buildUrl($apiRoute);

        $this->lastReponse = $this->connector->get($apiUrl, $this);

        return array_pop($this->lastReponse['result']);
    }

    /**
     * findAll
     *
     * @param params array An array of filters.
     * @return Array of model objects according the repository class name, if existing, otherwise array of arrays. Indexed by id.
     */
    public function findAll()
    {
        $target = $this->getApiTarget();
        $apiRoute = str_replace(ApiRepository::PLACEHOLDER_TARGET, $target, $this->apiRouteFindByQuery);
        $apiUrl = $this->buildUrl($apiRoute, array ('lang' => $this->typo3Services->getLang()));

        $this->lastReponse = $this->connector->get($apiUrl, $this);

        return $this->lastReponse['result'];
    }
    
    /**
     * @see \Xima\XmTools\Classes\Typo3\Domain\Repository\Repository::findAllByQuery()
     */
    public function findAllByQuery (\Xima\XmTools\Classes\Typo3\Query\QueryInterface $query)
    {
        $params = $query->getParams();
        
        $target = $this->getApiTarget();
        $apiRoute = str_replace(ApiRepository::PLACEHOLDER_TARGET, $target, $this->apiRouteFindByQuery);
        $apiUrl = $this->buildUrl($apiRoute, $params);

        $this->lastReponse = $this->connector->get($apiUrl, $this);

        return $this->lastReponse['result'];
    }

    public function createQuery()
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $query = $objectManager->get('Xima\XmTools\Classes\API\REST\Query', $this);

        return $query;
    }

    /**
     * Builds the URL to API. The API schema must be definedin the TYPO3 constant editor to something like:
     * -[Api-URL]/[Api-Key][Api-Route]
     * -[Api-URL][Api-Route]?[Api-Key]
     * -...
     *
     * @param $route string
     * @param $params array
     *
     * @return string
     */
    private function buildUrl($route, $params = array())
    {
        $placeHolders = array(ApiRepository::PLACEHOLDER_API_URL, ApiRepository::PLACEHOLDER_API_ROUTE, ApiRepository::PLACEHOLDER_API_KEY);
        $replace = array($this->apiUrl, $route, $this->apiKey);
        $url = str_replace($placeHolders, $replace, $this->apiSchema);

        if (!empty($params)) {
            // handle params
            $paramsAsString = array();
            $queryString = '';
            foreach ($params as $key => $value) 
            {
                if (is_array($value))
                {
                    $value = implode(',', $value);    
                }
                $paramsAsString [] = $key.'='.urlencode($value);
            }
            if (! empty($paramsAsString)) 
            {
                $queryString = implode('&', $paramsAsString);
            }

            $url .= (strstr($url, '?')) ? '&' : '?';
            $url .= $queryString;
        }
        
        return $url;
    }

    private function getApiTarget()
    {
        $reflect = new \ReflectionClass($this);
        $route = strtolower(str_replace('Repository', '', $reflect->getShortName()));

        return $route;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    public function getApiSchema()
    {
        return $this->apiSchema;
    }

    public function setApiSchema($apiSchema)
    {
        $this->apiSchema = $apiSchema;
    }

    public function getApiRouteFindById()
    {
        return $this->apiRouteFindById;
    }

    public function setApiRouteFindById($apiRouteFindById)
    {
        $this->apiRouteFindById = $apiRouteFindById;
    }

    public function getApiRouteFindByQuery()
    {
        return $this->apiRouteFindByQuery;
    }

    public function setApiRouteFindByQuery($apiRouteFindByQuery)
    {
        $this->apiRouteFindByQuery = $apiRouteFindByQuery;
    }

    /**
     * To make it compatible with Typo3
     */
    public function getObjectType()
    {
        return $this->objectType;
    }

    public function getLastReponse() {

        return $this->lastReponse;
    }

    public function setLastReponse($lastReponse) {

        $this->lastReponse = $lastReponse;
        return $this;
    }
 
}
