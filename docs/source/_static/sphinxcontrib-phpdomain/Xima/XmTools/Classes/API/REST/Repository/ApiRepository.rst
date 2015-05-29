------------------------------------------------------------
Xima\\XmTools\\Classes\\API\\REST\\Repository\\ApiRepository
------------------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\API\\REST\\Repository

.. php:class:: ApiRepository

    Abstract class for extbase repositories to retrieve data through a REST URL.
    The concrete repository must provide the API route relative to the API URL configured in
    the TYPO3 constant editor for the concrete extension.

    .. php:attr:: extensionManager

        protected \Xima\XmTools\Classes\Typo3\Extension\ExtensionManager

    .. php:attr:: connector

        protected \Xima\XmTools\Classes\API\REST\Connector

        connector.

    .. php:attr:: typo3Services

        protected \Xima\XmTools\Classes\Typo3\Services

    .. php:attr:: apiKey

        protected

    .. php:attr:: apiUrl

        protected

    .. php:attr:: apiSchema

        protected

    .. php:attr:: apiRouteFindById

        protected

    .. php:attr:: apiRouteFindByQuery

        protected

    .. php:attr:: lastReponse

        protected

    .. php:method:: initializeObject()

    .. php:method:: findByUid($id)

        find.

        :param $id:
        :returns: The model object with the given id.

    .. php:method:: findAll()

        findAll.

        :returns: Array of model objects according the repository class name, if existing, otherwise array of arrays. Indexed by id.

    .. php:method:: findAllByQuery(Xima\XmTools\Classes\Typo3\Query\QueryInterface $query)

        :type $query: Xima\XmTools\Classes\Typo3\Query\QueryInterface
        :param $query:

    .. php:method:: createQuery()

    .. php:method:: buildUrl($route, $params = array())

        Builds the URL to API. The API schema must be definedin the TYPO3 constant
        editor to something like:
        -[Api-URL]/[Api-Key][Api-Route]
        -[Api-URL][Api-Route]?[Api-Key]
        -...

        :param $route:
        :param $params:
        :returns: string

    .. php:method:: getApiTarget()

    .. php:method:: getApiKey()

    .. php:method:: setApiKey($apiKey)

        :param $apiKey:

    .. php:method:: getApiUrl()

    .. php:method:: setApiUrl($apiUrl)

        :param $apiUrl:

    .. php:method:: getApiSchema()

    .. php:method:: setApiSchema($apiSchema)

        :param $apiSchema:

    .. php:method:: getApiRouteFindById()

    .. php:method:: setApiRouteFindById($apiRouteFindById)

        :param $apiRouteFindById:

    .. php:method:: getApiRouteFindByQuery()

    .. php:method:: setApiRouteFindByQuery($apiRouteFindByQuery)

        :param $apiRouteFindByQuery:

    .. php:method:: getObjectType()

        To make it compatible with Typo3.

    .. php:method:: getLastReponse()

    .. php:method:: setLastReponse($lastReponse)

        :param $lastReponse:

    .. php:method:: findBy($criteria, $orderBy = null, $limit = null, $offset = null)

        Finds entities by a set of criteria.

        :param $criteria:
        :type $orderBy: array|null
        :param $orderBy:
        :param $limit:
        :param $offset:
        :returns: array The objects.
