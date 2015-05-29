----------------------------------------------------------
Xima\\XmTools\\Classes\\ViewHelpers\\Control\\IfViewHelper
----------------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\ViewHelpers\\Control

.. php:class:: IfViewHelper

    Combines two conditions with AND or OR.

    .. php:method:: render($conditions, $conditionType = 'OR')

        renders <f:then> child if $conditions and/or combined is true, otherwise
        renders <f:else> child.

        :param $conditions:
        :type $conditionType: string
        :param $conditionType:
        :returns: string the rendered string
