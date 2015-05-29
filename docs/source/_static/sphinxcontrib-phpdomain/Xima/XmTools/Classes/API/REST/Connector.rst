--------------------------------------------
Xima\\XmTools\\Classes\\API\\REST\\Connector
--------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\API\\REST

.. php:class:: Connector

    The Api facade. The API configuration must be done through the TYPO3 constant editor for the concrete extension.

    .. php:attr:: extension

        protected \Xima\XmTools\Classes\Typo3\Model\Extension

    .. php:attr:: typo3Services

        protected \Xima\XmTools\Classes\Typo3\Services

    .. php:attr:: cacheManager

        protected \Xima\XmTools\Classes\Typo3\Cache\ApiCacheManager

    .. php:method:: get($url, Xima\XmTools\Classes\API\REST\Repository\ApiRepository $repository, $params = array())

        Gets called by
        repositories inheriting from
        Xima\XmTools\Classes\API\REST\Repository\AbstractApiRepository, retrieves
        JSON responses, converts
        arrays to objects according to the given repository class name (if
        existing) or to array of arrays.
        Translates values to the current or fallback language when fields with the
        following patterns are found:
        -nameDe, nameEn...
        -name_de, name_en...
        Calls cache or calls API and stores result in cache if older than one day.

        :param $url:
        :type $repository: Xima\XmTools\Classes\API\REST\Repository\ApiRepository
        :param $repository:
        :param $params:
        :returns: array

    .. php:method:: setExtension(Xima\XmTools\Classes\Typo3\Model\Extension $extension)

        :type $extension: Xima\XmTools\Classes\Typo3\Model\Extension
        :param $extension:
