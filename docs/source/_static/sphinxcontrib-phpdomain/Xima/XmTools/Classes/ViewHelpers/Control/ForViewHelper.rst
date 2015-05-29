-----------------------------------------------------------
Xima\\XmTools\\Classes\\ViewHelpers\\Control\\ForViewHelper
-----------------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\ViewHelpers\\Control

.. php:class:: ForViewHelper

    View helper with for loop like for int i = 0; i++; ...

    Define this in views as follows:
    {namespace mk=Tx_MyExt_ViewHelpers}

    <mk:for min="0" max="{value}" variable="i">{i}</mk:for>

    .. php:method:: render($max, $min = 0, $step = 1, $variableName = 'i')

        Returns selected="selected", if $value is contained in $inData.

        :param $max:
        :param $min:
        :param $step:
        :type $variableName: string
        :param $variableName: variable name
