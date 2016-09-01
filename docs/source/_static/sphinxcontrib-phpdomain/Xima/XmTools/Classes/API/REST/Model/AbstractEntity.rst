--------------------------------------------------------
Xima\\XmTools\\Classes\\API\\REST\\Model\\AbstractEntity
--------------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\API\\REST\\Model

.. php:class:: AbstractEntity

    AbstractEntity.

    Base class for models that get constructed by the \Xima\XmTools\Classes\API\REST\Connector. API data is returned as json, converted to an array and then iterated to instantiate model classes.
    Objects get hydrated by netresearch/jsonmapper.

    .. php:attr:: id

        protected int

    .. php:method:: postMapping()

        Hook to perform actions after json mapping.

    .. php:method:: setUid($uid)

        Sets the uid (suggested for the TYPO3 environment).

        :type $uid: int
        :param $uid:

    .. php:method:: getId()

    .. php:method:: setId($id)

        :param $id:
