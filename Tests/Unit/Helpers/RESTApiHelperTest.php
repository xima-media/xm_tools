<?php
/**
 * The Api facade test
 *
 * @author Wolfram Eberius <woe@xima.de>
 * @version 1.0.0
 */
class Tx_XmTools_Helper_RESTApiHelperTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
    /**
     * Fixture
     * @var TxXmTools\Classes\Helpers\RESTApiHelper
     */
    protected $fixture;
    
    /**
     * @var Tx_Phpunit_Framework
     */
    private $testingFramework;
    
    protected $result;
    
    public function setUp() {
        $this->setVerboseErrorHandler();
        $this->testingFramework = new Tx_Phpunit_Framework('tx_xm_tools');
        
        $this->fixture = $this->objectManager->get ('TxXmTools\Classes\Helpers\RESTApiHelper');
        
        $apiUrl = 'http://p203673.webspaceconfig.de/tmgs-db/api';
        $apiKey = '6c9608d7151cede45217f7bd49c6777a';
        $apiRoute = '/attributes?attr=accommodationCategories';
        
        $this->fixture->setApiUrl($apiUrl);
        $this->fixture->setApiKey($apiKey);
        $this->fixture->setIsApiCacheEnabled(false);
        $this->fixture->setFallbackLanguage('de');
        
        $this->result = $this->fixture->get($apiRoute, $this->objectManager->get ('Tx_XmAccommodations_Domain_Repository_AccommodationCategoryRepository'));
        
    }
    
    public function tearDown() {
        $this->testingFramework->cleanUp();
    
        unset($this->fixture, $this->testingFramework);
    }

    /**
     * @test
     */
    public function testUrlBuilding() {
        
        $this->assertSame(
            'http://p203673.webspaceconfig.de/tmgs-db/api/6c9608d7151cede45217f7bd49c6777a/attributes?attr=accommodationCategories&lang=',
            $this->fixture->getUrl()
        );
        
    }
    
    /**
     * @test
     */
    public function testAPI() {
        
        var_dump($this->result);
        $assert = count($this->result['result']) >= 3;
        
        $this->assertTrue ($assert);
    }
    
    protected function setVerboseErrorHandler()
    {
        $handler = function($errorNumber, $errorString, $errorFile, $errorLine) {
            echo "$errorString<br>\nFile: $errorFile (Line: $errorLine)<br><br>\n\n";
        };
        set_error_handler($handler);
    }
}    