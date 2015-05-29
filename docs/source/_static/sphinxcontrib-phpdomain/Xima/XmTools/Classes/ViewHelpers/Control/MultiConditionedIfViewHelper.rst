--------------------------------------------------------------------------
Xima\\XmTools\\Classes\\ViewHelpers\\Control\\MultiConditionedIfViewHelper
--------------------------------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\ViewHelpers\\Control

.. php:class:: MultiConditionedIfViewHelper

    Combines two conditions with AND or OR.

    .. php:method:: render($condition1, $condition2, $conditionType = '')

        renders <f:then> child if $condition and/or $or is true, otherwise renders
        <f:else> child.

        :param $condition1:
        :param $condition2:
        :type $conditionType: string
        :param $conditionType:
        :returns: string the rendered string
