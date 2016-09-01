-------------------------------------
Xima\\XmTools\\Classes\\Typo3\\FeUser
-------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Typo3

.. php:class:: FeUser

    Helper for FeUser

    .. php:method:: __construct()

        Constructor

    .. php:method:: getUid()

        :returns: int

    .. php:method:: getUser()

        :returns: array

    .. php:method:: getGroupData()

        :returns: array

    .. php:method:: feUserIsAuthenticated()

        Checks if fe_user is authenticated.

        :returns: bool

    .. php:method:: feUserHasRole($role)

        Checks if fe_user is authenticated and has given role.

        :type $role: string
        :param $role:
        :returns: bool

    .. php:method:: feUserHasRoleId($roleId)

        Checks if fe_user is authenticated and has given role id.

        :type $roleId: int
        :param $roleId:
        :returns: bool
