-------------------------------------------------------------
Xima\\XmTools\\Classes\\Typo3\\Controller\\AbstractController
-------------------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Typo3\\Controller

.. php:class:: AbstractController

    AbstractController.

    .. php:attr:: session

        protected \Xima\XmTools\Classes\Typo3\SessionManager

    .. php:attr:: typo3Services

        protected \Xima\XmTools\Classes\Typo3\Services

    .. php:method:: assignDefaultVariables()

    .. php:method:: addAssets($action)

        Adds stylesheets and javascripts to page head by action.

        :type $action: string
        :param $action: The current action.
        :returns: bool

    .. php:method:: getSession()

    .. php:method:: setSession($session)

        :param $session:

    .. php:method:: getTypo3Services()

    .. php:method:: setTypo3Services($typo3Services)

        :param $typo3Services:
