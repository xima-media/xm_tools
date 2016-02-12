<?php

namespace Xima\XmTools\Classes\API\REST;

use Xima\XmTools\Classes\Helper\Helper;

/**
 * The Api facade. The API configuration must be done through the TYPO3 constant editor for the concrete extension.
 *
 * @author Wolfram Eberius <woe@xima.de>
 */
class Connector
{
    /**
     * The extension that uses the Connector.
     *
     * @var \Xima\XmTools\Classes\Typo3\Model\Extension
     */
    protected $extension;

    /**
     * @var \Xima\XmTools\Classes\Typo3\Services
     * @inject
     */
    protected $typo3Services;

    /**
     * @var \Xima\XmTools\Classes\Typo3\Cache\ApiCacheManager
     * @inject
     */
    protected $cacheManager;

    /**
     * Gets called by repositories inheriting from Xima\XmTools\Classes\API\REST\Repository\AbstractApiRepository,
     * retrieves JSON responses, converts arrays to objects according to the given repository class name (if existing) or to array of arrays.
     * Translates values to the current or fallback language when fields with the following patterns are found:
     * -nameDe, nameEn...
     * -name_de, name_en...
     * Calls cache or calls API and stores result in cache if older than one day.
     *
     * @param string $url
     * @param \Xima\XmTools\Classes\API\REST\Repository\ApiRepository $repository
     * @param array $params
     *
     * @return array
     */
    public function get($url, \Xima\XmTools\Classes\API\REST\Repository\ApiRepository $repository)
    {
        $repositoryClassName = get_class($repository);
        $modelClassName = str_replace('\Repository', '\Model', $repositoryClassName);
        $modelClassName = str_replace('Repository', '', $modelClassName);

        $isApiCacheEnabled = $this->extension->getSettings()['api']['isCacheEnabled'];
        $fallbackLanguage = $this->extension->getSettings()['fallbackLanguage'];

        $responseJson = false;
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        $logger = $objectManager->get('Xima\XmTools\Classes\Typo3\Logger');
        /* @var $logger \Xima\XmTools\Classes\Typo3\Logger */
        $session = $objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\Session');

        $logger->log('Called api url: ' . $url);

        // retrieve data from cache or api
        if ($isApiCacheEnabled) {
            $responseJson = $this->cacheManager->get($url);

            if ($responseJson) {
                $logger->log('Got api data from cache.');
            }
        }

        if (!$responseJson) {
            $logger->log('Try to get data from api.');
            $responseJson = file_get_contents($url);

            //write to api
            if ($isApiCacheEnabled) {
                $this->cacheManager->write($url, $responseJson);
            }
        }

        $response = json_decode($responseJson);

        if (array_key_exists('result', $response)) {
            $logger->log('Success.');

            //translate the result
            $targetLanguage = (isset($response->metadata->lang)) ? ($response->metadata->lang) : $this->typo3Services->getLang();

            if (is_array($response->result)) {
                foreach ($response->result as $key => $result) {
                    $response->result[$key] = Helper::translate($result, $targetLanguage, $fallbackLanguage);
                }
            } else if (isset($response->result->id)) {
                // if it is a single result and a single result was queried we still want to return an array of arrays
                $response->result = array(
                    $response->result->id => $response->result);
            }

            //map json data to objects if a class exists
            if (class_exists($modelClassName)) {
                $mapper = new \JsonMapper();

                $objectsJson = $response->result;
                $response->result = array();

                foreach ($objectsJson as $objectJson) {
                    $object = $mapper->map($objectJson, new $modelClassName());
                    if (method_exists($object, 'getId')) {
                        //check if the object is already registered
                        $testObject = $session->getObjectByIdentifier($modelClassName, $object->getId());
                        if ($testObject) {
                            $object = $testObject;
                        } else {
                            //register object to e.g. render option tags with correct identifier values
                            $session->registerObject($object, $object->getId());
                        }
                    }
                    if (is_a($object, '\Xima\XmTools\Classes\API\REST\Model\AbstractEntity')) {
                        $object->postMapping();
                    }

                    $response->result[] = $object;
                }
            }
        } else {
            $errorMessage = 'Api data not available for extension \'' . $this->extension->getName() . '\'';
            trigger_error($errorMessage, E_USER_WARNING);
            $logger->log($errorMessage);

            $response->result = array();
        }

        return $response;
    }

    public function post($url, $entity)
    {
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $jsonData = $serializer->serialize($entity, 'json');

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post($url, [
                'body' => $jsonData
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (\Exception $e) {
            $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
            $logger = $objectManager->get('Xima\XmTools\Classes\Typo3\Logger');
            $logger->log($e->getMessage());

            return false;
        }
    }

    /**
     * Sets the current extension and the cache path accoring to the extension key.
     *
     * @param \Xima\XmTools\Classes\Typo3\Model\Extension
     *
     * @return \Xima\XmTools\Classes\API\REST\Connector
     */
    public function setExtension(\Xima\XmTools\Classes\Typo3\Model\Extension $extension)
    {
        $this->extension = $extension;
        $this->cacheManager->setPath($extension->getKey());

        return $this;
    }
}
