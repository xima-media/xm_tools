ke_search
---------

To ease the use of `Custom indexers <https://www.typo3-macher.de/facettierte-suche-ke-search/dokumentation/ein-eigener-indexer/>`_ for the search extension `ke_search <https://typo3.org/extensions/repository/view/ke_search>`_,
xm_tools offers two components:

- Tha value object :php:class:`IndexEntry` class for new index entries.
- The :php:class:`AbstractIndexer`, which your custom indexer may extend to care for registering the indexer and storing *IndexEntry* objects.

Usage example:

::

    class BlogIndexer extends AbstractDWIDBIndexer
    {
        const TYPE = 'blog';

        public function customIndexer(&$indexerConfig, &$indexerObject)
        {
            $content = '';

            if ($indexerConfig['type'] == self::TYPE) {

                $repository = $objectManager->get('\Xima\BlogExampleExtension\Domain\Repository\BlogRepository');
                $blogs = $repository->findAll();

                foreach ($blogs as $blog)
                {
                    $indexEntry = new IndexEntry();
                    $indexEntry->setTitle($blog->getTitle());
                    $indexEntry->setAdditionalFields(...);
                    // add more entry data

                    parent::storeInIndex($indexEntry, $indexerObject, $indexerConfig);
                }

                $content = parent::getReport($indexerConfig, $blogs);
            }

            return $content;
        }

        protected function getType()
        {
            return self::TYPE;
        }

        protected function getName()
        {
            return 'BlogIndexer';
        }
    }
