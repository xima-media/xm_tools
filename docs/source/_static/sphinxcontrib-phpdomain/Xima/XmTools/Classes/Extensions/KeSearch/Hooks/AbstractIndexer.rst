--------------------------------------------------------------------
Xima\\XmTools\\Classes\\Extensions\\KeSearch\\Hooks\\AbstractIndexer
--------------------------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Extensions\\KeSearch\\Hooks

.. php:class:: AbstractIndexer

    Hooks for ke_search.

    .. php:attr:: objectManager

        protected \TYPO3\CMS\Extbase\Object\ObjectManager

        objectManager.

    .. php:method:: __construct()

    .. php:method:: getType()

    .. php:method:: getName()

    .. php:method:: registerIndexerConfiguration($params, $pObj)

        :param $params:
        :param $pObj:

    .. php:method:: storeInIndex(IndexEntry $indexEntry, tx_kesearch_indexer $indexerObject, $indexerConfig)

        :type $indexEntry: IndexEntry
        :param $indexEntry:
        :type $indexerObject: tx_kesearch_indexer
        :param $indexerObject:
        :param $indexerConfig:

    .. php:method:: getReport($indexerConfig, $objects, IndexLog $indexLog = null)

        :param $indexerConfig:
        :param $objects:
        :type $indexLog: IndexLog
        :param $indexLog:
