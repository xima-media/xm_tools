---------------------------------------------------------
Xima\\XmTools\\Classes\\Typo3\\Extension\\ExtensionHelper
---------------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Typo3\\Extension

.. php:class:: ExtensionHelper

    .. php:attr:: configurationManagerInterface

        protected \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface

    .. php:method:: getExtension($extensionName = null)

        :type $extensionName: string
        :param $extensionName:
        :returns: \Xima\XmTools\Classes\Typo3\Model\Extension

    .. php:method:: getExtensionKeyByExtensionName($extensionName)

        converts a Typo3 extension name to the extension key

        :type $extensionName: string
        :param $extensionName:

    .. php:method:: getConfigurationBE($extensionName)

        :param $extensionName:

    .. php:method:: getConfigurationFE($extensionName)

        :param $extensionName:
