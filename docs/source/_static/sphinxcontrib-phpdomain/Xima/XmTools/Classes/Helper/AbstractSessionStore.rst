----------------------------------------------------
Xima\\XmTools\\Classes\\Helper\\AbstractSessionStore
----------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Helper

.. php:class:: AbstractSessionStore

    Abstract class for storing session data.

    .. php:attr:: data

        protected

    .. php:method:: set($key, $value)

        Write data into session.

        :type $key: string
        :param $key:
        :param $value:
        :returns: bool

    .. php:method:: get($key)

        Restore data from session.

        :type $key: string
        :param $key:
        :returns: mixed

    .. php:method:: cleanUp()

        Clean up all session data.

        :returns: bool
