<?php
namespace TxXmTools\Classes\Helpers;

use \t3lib_extMgm;
use \t3lib_div;
/**
 * TYPO3 Services
 *
 * @author Steve Weinert <kontakt@steve-weinert.de>
 * @copyright (c) 2013, Steve Weinert
 * @version 1.7.0
 */
class Typo3Services {
    protected $extensionKey = null;
    protected $extensionName = null;
    protected $relPath = null;
    protected $extPath = null;
    protected $langId = null;
    protected $lang = null;
    protected $jsRelPath = 'Resources/Public/js/';
    protected $cssRelPath = 'Resources/Public/css/';
    protected $configuration;

    /**
	 *
	 * @var Tx_Extbase_Configuration_ConfigurationManagerInterface
	 * @inject
	 */
    protected $configurationManagerInterface;

    public function __construct()
    {
        $objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');

        if ( ! $this->configurationManagerInterface){
            $this->configurationManagerInterface = $objectManager->get('Tx_Extbase_Configuration_ConfigurationManagerInterface');
        }

        $this->initializeObject();
    }

    public function initializeObject() {

        /* not clear if possibe at all to get the right plugin setings here */
        if(TYPO3_MODE === 'BE') {

            $configurationManager = \t3lib_div::makeInstance('\Tx_Extbase_Configuration_BackendConfigurationManager');
            //var_dump($configurationManager);

            /*$this->settings = $configurationManager->getConfiguration(
                    $this->request->getControllerExtensionName(),
                    $this->request->getPluginName()

                    TS: module.tx_foo.settings < plugin.tx_foo.settings
            );*/

        }
        else
        {
            $this->configuration = $this->configurationManagerInterface->getConfiguration (\Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
            $this->extensionName = $this->configuration ['extensionName'];

            $this->extensionKey = Typo3Services::getExtensionKeyByExtensionName ($this->extensionName);
            $this->relPath = \t3lib_extMgm::siteRelPath ($this->extensionKey);
            $this->extPath = \t3lib_extMgm::extPath($this->extensionKey);
            $this->langId = $GLOBALS ['TSFE']->sys_language_uid;
            $this->lang = $GLOBALS ['TSFE']->lang;
        }
    }

	/**
     * Fügt HTML-Code zum &lt;head&gt; hinzu
     * @param string $html
     */
    public function addToHead($html) {
        $GLOBALS['TSFE'] -> additionalHeaderData[$this->extensionKey] .= $html;
    }

    /**
	 * Binds JavaScript files in the HTML head of the page (TYPO3)
	 *
	 * @param array $files
	 *        file names, starting with http or relative
	 */
    public function includeJavaScript(array $files) {

        $markup = '<script type="text/javascript" src="%s"></script>';

        $i = 0;
        foreach ( $files as $file ) {
                $url = (preg_match ('~^http~', $file)) ? $file : $this->relPath . $this->jsRelPath . $file;

                $GLOBALS ['TSFE']->additionalHeaderData [$this->extensionKey . '-js-' . $i] = sprintf ($markup, $url);
                $i++;
        }
    }

    /**
     * Binds JavaScript files by Typoscript config in the HTML head of the page (TYPO3)
     *
     * @param array $config
     *        	Key value array with path to file
     * @param array $keys
     *        	Array of file keys
     */
    public function includeJavaScriptByTypoScript(array $config, array $keys) {

        $files = array();

        foreach ( $keys as $key ) {
            if (array_key_exists ($key, $config)) {
                $files[] = $config [$key];
            }
        }

        $this->includeJavaScript($files);
    }

    /**
	 * Binds CSS files in the HTML head of the page (TYPO3)
	 *
	 * @param array $files
	 *        file names, starting with http or relative
	 */
    public function includeCss(array $files) {

        $markup = '<link href="%s" type="text/css" rel="stylesheet" media="screen" />';

        $i = 0;
        foreach ( $files as $file ) {
                $url = (preg_match ('~^http~', $file)) ? $file : $this->relPath . $this->cssRelPath . $file;

                $GLOBALS ['TSFE']->additionalHeaderData [$this->extensionKey . '-css-' . $i] = sprintf ($markup, $url);
                $i++;
        }
    }

    /**
     * Binds CSS files by Typoscript config in the HTML head of the page (TYPO3)
     *
     * @param array $config
     *        	Key value array with path to file
     * @param array $keys
     *        	Array of file keys
     */
    public function includeCssByTypoScript(array $config, array $keys) {

        $files = array();

        foreach ( $keys as $key ) {
            if (array_key_exists ($key, $config)) {
                $files[] = $config [$key];
            }
        }

        $this->includeCss($files);
    }

    /**
	 * Gibt die Real-Url oder die PageID (?id=[PID]) zurück
	 *
	 * @param int $pageId
	 * @param boolean $idAsGet
	 * @return string
	 */
    public function getUrlByPid($pageId, $idAsGet = false) {

        $res = null;
        if ($idAsGet) {
            return $_SERVER ['PHP_SELF'] . '?id=' . $pageId;
        }

        $sql = 'SELECT page_id, pagepath, language_id' . ' FROM tx_realurl_pathcache WHERE page_id=' . intval ($pageId) . ' AND language_id=' . intval ($this->langId) . ' LIMIT 1';
        if (! ($res = $GLOBALS ['TYPO3_DB']->sql_query ($sql))) {
            return;
        }

        $results = array ();
        while ( $row = $GLOBALS ['TYPO3_DB']->sql_fetch_assoc ($res) ) {
            $results [] = $row;
        }

        if (! empty ($results) && isset ($results [0]) && isset ($results [0] ['pagepath'])) {
            return $results [0] ['pagepath'];
        }
        else {
            return $_SERVER ['PHP_SELF'] . '?id=' . $pageId;
        }
    }

    /**
	 * Returns the base URL for GET-Request with ending ? od &
	 *
	 * @param int $pageId
	 * @param boolean $idAsGet
	 * @return string
	 */
    public function getBaseUrlForGetRequestByPid($pageId, $idAsGet = false) {

        if ($pageId) {
            $url = $this->getUrlByPid ($pageId, $idAsGet);
            $url .= preg_match ('~\?~', $url) ? '&' : '?';
        }
        else {
            $url = false;
        }

        return $url;
    }

    /**
     * Registriert Flexforms.<br />
     * Benutzbar in <i>ext_tables.php</i>.
     */
    static function addFlexforms($extensionKey, $pluginName, $flexformName) {
        $extensionName = t3lib_div::underscoredToUpperCamelCase($extensionKey);
        $pluginSignature = strtolower($extensionName);

        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature . '_' . strtolower($pluginName)] = 'pi_flexform';
        t3lib_extMgm::addPiFlexFormValue($pluginSignature . '_' . strtolower($pluginName), 'FILE:EXT:' . $extensionKey . '/Configuration/FlexForms/' . $flexformName);
    }

	/**
     * @param string $extensionName
     *
     * converts a Typo3 extension name to the extension key
     */
    static function getExtensionKeyByExtensionName($extensionName)
    {
    	$extensionKey = '';

    	for ($i=0; $i < strlen($extensionName); $i++)
    	{
    		$chr = mb_substr ($extensionName, $i, 1, "UTF-8");

    		if ((mb_strtolower($chr, "UTF-8") != $chr) && $i>0)
    		{
    			$extensionKey .= '_';
    		}

    		$extensionKey .= $chr;
    	}

    	$extensionKey = strtolower($extensionKey);

    	return $extensionKey;
    }

    public function getExtensionKey() {

        return $this->extensionKey;
    }

    public function setExtensionKey($extensionKey) {

        $this->extensionKey = $extensionKey;
    }

    public function getExtensionName() {

        return $this->extensionName;
    }

    public function setExtensionName($extensionName) {

        $this->extensionName = $extensionName;
    }

    public function getRelPath() {

        return $this->relPath;
    }

    public function setRelPath($relPath) {

        $this->relPath = $relPath;
    }

    public function getLangId() {

        return $this->langId;
    }

    public function setLangId($langId) {

        $this->langId = $langId;
    }

    public function getLang() {

        return $this->lang;
    }

    public function setLang($lang) {

        $this->lang = $lang;
    }

    public function getSettings()
    {
        return $this->configuration['settings'];
    }

    public function getExtPath(){
        return $this->extPath;
    }

    public function setExtPath($extPath){
        $this->extPath = $extPath;
    }

    /**
     * Set the title of the single view page to a custom defined title
     *
     * @param string $title
     */
    public function substitutePageTitle($title)
    {
        $GLOBALS['TSFE']->content = preg_replace('/<title>.+<\/title>/U', '<title>' . $title . '</title>', $GLOBALS['TSFE']->content);
    }

    /**
     * Set the title of the single view page to a custom defined title
     *
     * @param string $title
     */
    public function prependPageTitle($title)
    {
        preg_match('/<title>.+<\/title>/U', $GLOBALS['TSFE']->content, $matches, PREG_OFFSET_CAPTURE);

        $hit = $matches[0][0];
        if ($hit != '') {
            $hit = str_replace(array ('<title>', '</title>'), '', $hit);
            $this->substitutePageTitle ($title . $hit);
        }
    }
}
