------------------------------------------------------------
Xima\\XmTools\\Classes\\API\\REST\\Repository\\ApiRepository
------------------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\API\\REST\\Repository

.. php:class:: ApiRepository

    Abstract class for extbase repositories to retrieve data through a REST API.
    The name of the inheriting repository class will be used for creating the API URL and instantiating model classes. This behaviour can be changed by overriding the function getApiTarget().

    .. php:attr:: extensionManager

        protected \Xima\XmTools\Classes\Typo3\Extension\ExtensionManager

    .. php:attr:: connector

        protected \Xima\XmTools\Classes\API\REST\Connector

        The connector class.

    .. php:attr:: typo3Services

        protected \Xima\XmTools\Classes\Typo3\Services

        The xm_tools facade.

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

    .. php:attr:: apiRouteCreate

        protected

    .. php:attr:: apiRouteUpdate

        protected

    .. php:attr:: lastReponse

        protected

    .. php:method:: initializeObject()

        Retrieves the calling extension through the package name of the inheriting
        repository class. Configures the repository with the extensions' settings.

    .. php:method:: findByUid($id)

        Find an entity by id.

        :param $id:
        :returns: \Xima\XmTools\Classes\API\REST\Model\AbstractEntity|array The model class or array with the given id.

    .. php:method:: findAll()

        findAll.

        :returns: Array of model objects according the repository class name, if existing, otherwise array of arrays. Indexed by id.

    .. php:method:: findAllByQuery(Xima\XmTools\Classes\Typo3\Query\QueryInterface $query)

        Find all entities filtered by a
        \Xima\XmTools\Classes\Typo3\Query\QueryInterface.

        :type $query: Xima\XmTools\Classes\Typo3\Query\QueryInterface
        :param $query: The filter object.
        :returns: Array of model objects

    .. php:method:: createQuery()

        Create a Xima\XmTools\Classes\API\REST\Query object. Overrides TYPO3
        default behavivour.

        :returns: \Xima\XmTools\Classes\API\REST\Query

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

    .. php:method:: persist(Xima\XmTools\Classes\API\REST\Model\AbstractEntity $entity)

        :type $entity: Xima\XmTools\Classes\API\REST\Model\AbstractEntity
        :param $entity:

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

    .. php:method:: getApiRouteCreate()

    .. php:method:: setApiRouteCreate($apiRouteCreate)

        :param $apiRouteCreate:

    .. php:method:: getApiRouteUpdate()

    .. php:method:: setApiRouteUpdate($apiRouteUpdate)

        :param $apiRouteUpdate:

    .. php:method:: findBy($criteria, $orderBy = null, $limit = null, $offset = null)

        Finds entities by a set of criteria.

        :param $criteria:
        :type $orderBy: array|null
        :param $orderBy:
        :param $limit:
        :param $offset:
        :returns: array The objects.
