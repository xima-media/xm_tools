-------------------------------------------------------------------
Xima\\XmTools\\Classes\\ViewHelpers\\Object\\ArrayImplodeViewHelper
-------------------------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\ViewHelpers\\Object

.. php:class:: ArrayImplodeViewHelper

    Implodes array members to string, optionally calls function on members before imploding.

    .. php:method:: render($glue, $array, $functionOrKey = '')

        Basically equal to PHP implode(). If array items are array themselves a
        key ($functionOrKey) can be specified.
        If array items are objects a function to retrieve a certain value for the
        implode can be specified ($functionOrKey).

        :param $glue:
        :param $array:
        :param $functionOrKey:
