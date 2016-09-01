--------------------------------------
Xima\\XmTools\\Classes\\Helper\\Helper
--------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Helper

.. php:class:: Helper

    Static helper methods, independent of context like TYPO3...

    .. php:method:: slugify($text)

        Modifies a string to remove all non ASCII characters and spaces.

        :param $text:

    .. php:method:: slugify2($string)

        :param $string:

    .. php:method:: slugify3($str, $replace = array(), $delimiter = '-')

        :param $str:
        :param $replace:
        :param $delimiter:

    .. php:method:: translate($objectToTranslate, $lang, $fallbackLang = '')

        Translate all translatable fields of a stdObject.

        :param $objectToTranslate:
        :type $lang: string
        :param $lang:
        :type $fallbackLang: string
        :param $fallbackLang:
        :returns: mixed

    .. php:method:: mergeTranslations($object, $translations)

        :param $object:
        :param $translations:

    .. php:method:: shortenText($text, $length, $glue = '', $finishString = '...')

        :param $text:
        :param $length:
        :param $glue:
        :param $finishString:

    .. php:method:: underscoreToCamelCase($string, $first_char_caps = true)

        Convert strings with underscores into CamelCase.

        :type $string: string
        :param $string: The string to convert
        :type $first_char_caps: bool
        :param $first_char_caps: camelCase or CamelCase
        :returns: string The converted string

    .. php:method:: getClassPackageName($class)

        Get the package name of a class.

        :type $class: mixed
        :param $class:
        :returns: string

    .. php:method:: getClassShortName($class)

        Get the short name of a class (class name without namespace).

        :type $class: mixed
        :param $class:
        :returns: string
