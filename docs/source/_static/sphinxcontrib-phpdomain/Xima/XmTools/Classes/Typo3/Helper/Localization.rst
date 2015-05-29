---------------------------------------------------
Xima\\XmTools\\Classes\\Typo3\\Helper\\Localization
---------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Typo3\\Helper

.. php:class:: Localization

    .. php:method:: getDictionary($additionalExtensionNames = array(), $lang = null)

        Get translations of current extension, xm_tools and optional any more
        extensions. Supports conversion to js file and loading it as well.

        :type $additionalExtensionNames: array
        :param $additionalExtensionNames: Other extensions to get translations of.
        :param $lang:
        :returns: \Xima\XmTools\Classes\Helper\Dictionary An array of translations by key in the current language.

    .. php:method:: printDictionary($additionalExtensionNames = array())

        Helper method for development: list all available translations of the
        selected extensions, ordered alphabetically.

        :param $additionalExtensionNames:

    .. php:method:: getTranslations(Xima\XmTools\Classes\Typo3\Model\Extension $extension, $lang)

        :type $extension: Xima\XmTools\Classes\Typo3\Model\Extension
        :param $extension:
        :param $lang:

    .. php:method:: getLangKey($lang)

        Returns the current lang key, 'default' if 'en'
        Specific return value for the XliffParser.

        :param $lang:
        :returns: string
