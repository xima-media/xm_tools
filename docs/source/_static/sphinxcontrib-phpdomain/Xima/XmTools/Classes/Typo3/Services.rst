---------------------------------------
Xima\\XmTools\\Classes\\Typo3\\Services
---------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Typo3

.. php:class:: Services

    XmTools' facade like base class.

    Initialises common settings such as current language and extension, global site parameters and managers. Includes static and non static helper functions for TYPO3.
    Include it by dependency injection.

    .. php:attr:: extensionManager

        protected \Xima\XmTools\Classes\Typo3\Extension\ExtensionManager

    .. php:attr:: extension

        protected \Xima\XmTools\Classes\Typo3\Model\Extension

        The current extension.

    .. php:attr:: langId

        protected

    .. php:attr:: lang

        protected

    .. php:attr:: parameters

        protected array

        The site parameters from parameters.yml.

    .. php:attr:: settings

        protected array

        The settings of the xm_tools extension.

    .. php:method:: initializeObject()

    .. php:method:: addToHead($html)

        Fügt HTML-Code zum &lt;head&gt; hinzu.

        :type $html: string
        :param $html:

    .. php:method:: includeJavaScript($files, Extension $extension = null)

        Binds JavaScript files in the HTML head of the page (TYPO3).

        :type $files: array
        :param $files: file names, starting with http or relative
        :type $extension: Extension
        :param $extension:

    .. php:method:: includeJavaScriptByTypoScript($config, $keys)

        Binds JavaScript files by Typoscript config in the HTML head of the page
        (TYPO3).

        :type $config: array
        :param $config: Key value array with path to file
        :type $keys: array
        :param $keys: Array of file keys

    .. php:method:: includeCss($files, Extension $extension = null)

        Binds CSS files in the HTML head of the page (TYPO3).

        :type $files: array
        :param $files: file names, starting with http or relative
        :type $extension: Extension
        :param $extension:

    .. php:method:: includeCssByTypoScript($config, $keys)

        Binds CSS files by Typoscript config in the HTML head of the page (TYPO3).

        :type $config: array
        :param $config: Key value array with path to file
        :type $keys: array
        :param $keys: Array of file keys

    .. php:method:: getUrlByPid($pageId, $idAsGet = false)

        Gibt die Real-Url oder die PageID (?id=[PID]) zurück.

        :param $pageId:
        :type $idAsGet: bool
        :param $idAsGet:
        :returns: string

    .. php:method:: getBaseUrlForGetRequestByPid($pageId, $idAsGet = false)

        Returns the base URL for GET-Request with ending ? od &.

        :param $pageId:
        :type $idAsGet: bool
        :param $idAsGet:
        :returns: string

    .. php:method:: addFlexforms($extensionKey, $pluginName, $flexformName)

        Registriert Flexforms.<br />
        Benutzbar in <i>ext_tables.php</i>.

        :param $extensionKey:
        :param $pluginName:
        :param $flexformName:

    .. php:method:: setPageTitle($title)

        Set the title of the single view page to a custom defined title.

        :type $title: string
        :param $title:

    .. php:method:: prependPageTitle($title)

        Set the title of the single view page to a custom defined title.

        :type $title: string
        :param $title:

    .. php:method:: getIsoLang()

    .. php:method:: getLangId()

    .. php:method:: setLangId($langId)

        :param $langId:

    .. php:method:: getLang()

    .. php:method:: setLang($lang)

        :param $lang:

    .. php:method:: getExtension()

    .. php:method:: setExtension($extension)

        :param $extension:

    .. php:method:: getExtensionManager()

    .. php:method:: setExtensionManager($extensionManager)

        :param $extensionManager:

    .. php:method:: getParameters()

    .. php:method:: setParameters($parameters)

        :param $parameters:

    .. php:method:: getSettings()

    .. php:method:: setSettings($settings)

        :param $settings:

    .. php:method:: getPageRenderer()

        :returns: \TYPO3\CMS\Core\Page\PageRenderer
