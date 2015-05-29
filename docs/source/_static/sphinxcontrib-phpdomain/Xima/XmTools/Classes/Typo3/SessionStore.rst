-------------------------------------------
Xima\\XmTools\\Classes\\Typo3\\SessionStore
-------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Typo3

.. php:class:: SessionStore

    Session store for TYPO3 Extbase.

    .. php:attr:: sessionKey

        protected

    .. php:attr:: data

        protected

    .. php:method:: set($key, $value)

        :param $key:
        :param $value:

    .. php:method:: get($key)

        :param $key:

    .. php:method:: cleanUp()

    .. php:method:: getSessionKey()

    .. php:method:: setSessionKey($sessionKey)

        Sets the session key and retrieves data if there is some for this session
        key. Essential to be called before using.

        :type $sessionKey: string
        :param $sessionKey:
        :returns: \Xima\XmTools\Classes\Typo3\SessionStore
