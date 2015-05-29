---------------------------------------------
Xima\\XmTools\\Classes\\Typo3\\FlexFormHelper
---------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Typo3

.. php:class:: FlexFormHelper

    TYPO3 FlexForm Helper.

    .. php:method:: buildOptionList(Tx_Extbase_Persistence_Repository $repository, $config, $label, $value)

        Get list of models attribute values from $repository->findAll().
        Useful to fill $config['items'] of flexform.

        :type $repository: Tx_Extbase_Persistence_Repository
        :param $repository:
        :param $config:
        :param $label:
        :param $value:
