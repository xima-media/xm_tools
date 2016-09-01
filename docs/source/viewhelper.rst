ViewHelper
==========

- ResponsiveImageViewHelper
    The :php:class:`ResponsiveImageViewHelper` can be used to process and render images with *<picture>* and subsequent *<source>* tags for images fitting the device loading the image.
- URLSafeViewHelper
    The :php:class:`URLSafeViewHelper` renders links, be it internal or external (workaround for https://forge.typo3.org/issues/72818).

Control
"""""""

- ForViewHelper
    The :php:class:`ForViewHelper` represents the for loop (e.g. `For loops in PHP <http://php.net/manual/en/control-structures.for.php>`_). You can name the loop variable if you want
    to access it in your template.
- IfViewHelper
    The :php:class:`IfViewHelper` can be used to render a template part depending on multiple conditions that can be AND or OR joined.

Form
""""

- AdvancedSelectViewHelper: ...

Object
""""""

- ArrayCheckViewHelper
    The :php:class:`ArrayCheckViewHelper` offers checks on arrays, such as *in_array()*, *empty()*. Possible conditions are 'IN','NOT_IN', 'NOT_FIRST', 'NOT_LAST', 'EMPTY', 'NOT_EMPTY', 'IS_ARRAY', 'IN_KEYS', 'NOT_IN_KEYS'.
    Usage example:
    ::

        {namespace xmTools = Xima\XmTools\Classes\ViewHelpers}
        <f:if condition="{xmTools:object.ArrayCheck(array:yourArray,needle:1,check:'IN')}">
            ...
        </f:if>

- ArrayExplodeViewHelper
    The :php:class:`ArrayExplodeViewHelper` encapsulates PHP's `explode() <http://php.net/manual/en/function.explode.php>`_ function.
    Usage example:
    ::

        {namespace xmTools = Xima\XmTools\Classes\ViewHelpers}
        <f:for each="{xmTools:object.ArrayExplode(delimiter:',',string:someString)}" as="item">
            ...do something with {item}
        </f:for>

- ArrayImplodeViewHelper
    The :php:class:`ArrayImplodeViewHelper` encapsulates PHP's `implode() <http://php.net/manual/en/function.implode.php>`_ function and displays the output. You can specify a key if the array is an array
    or a property or function if the array is an array of objects.
    Usage example:
    ::

        {namespace xmTools = Xima\XmTools\Classes\ViewHelpers}
        <xmTools:object.ArrayImplode glue=", " array="{someArray}">

- StrReplaceViewHelper
    The :php:class:`StrReplaceViewHelper` encapsulates PHP's `str-replace() <http://php.net/manual/en/function.str-replace.php>`_ function.
- StrtolowerViewHelper
    The :php:class:`StrtolowerViewHelper` encapsulates PHP's `strtolower() <http://php.net/manual/en/function.strtolower.php>`_ function.
- StrtoupperViewHelper
    The :php:class:`StrtoupperViewHelper` encapsulates PHP's `strtoupper() <http://php.net/manual/en/function.strtoupper.php>`_ function.
