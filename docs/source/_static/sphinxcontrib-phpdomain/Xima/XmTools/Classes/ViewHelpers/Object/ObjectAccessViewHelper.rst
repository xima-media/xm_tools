-------------------------------------------------------------------
Xima\\XmTools\\Classes\\ViewHelpers\\Object\\ObjectAccessViewHelper
-------------------------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\ViewHelpers\\Object

.. php:class:: ObjectAccessViewHelper

    Allows accessing an array's value by its key or object's property or method by its name
    in order to achieve a "dynamic" path in Fluid, kind of
    {fooarray.{dynamic}}, which is not possible yet, can be replaced
    with {my:objectAccess(haystack: fooarray, needle: key)}.

    .. php:method:: render($haystack, $needle, $methodPrefix = null)

        :param $haystack:
        :type $needle: string
        :param $needle:
        :type $methodPrefix: string
        :param $methodPrefix:
        :returns: mixed
