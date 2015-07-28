---------------------------------------------
Xima\\XmTools\\Classes\\Typo3\\SessionManager
---------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Typo3

.. php:class:: SessionManager

    TYPO3 session manager. Managages the retrieval of selected TYPO3 session stores.

    .. php:attr:: typo3Services

        protected \Xima\XmTools\Classes\Typo3\Services

    .. php:attr:: sessionStores

        protected

    .. php:method:: set($key, $value, $postfix = null, Xima\XmTools\Classes\Typo3\Model\Extension $extension = null)

        Write data into session.

        :param $key:
        :param $value:
        :param $postfix:
        :type $extension: Xima\XmTools\Classes\Typo3\Model\Extension
        :param $extension: store session data for another extension

    .. php:method:: get($key, $postfix = null, $extension = null)

        Restore data from session.

        :type $key: string
        :param $key:
        :param $postfix:
        :param $extension:
        :returns: mixed

    .. php:method:: cleanUp($postfix = null, $extension = null)

        Clean up session.

        :param $postfix:
        :param $extension:

    .. php:method:: getSession($postfix = null, $extension = null)

        :param $postfix:
        :param $extension:

    .. php:method:: getSessionStores()

    .. php:method:: setSessionStores($sessionStores)

        :param $sessionStores:
