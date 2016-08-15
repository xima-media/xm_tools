Filter-List-Flow
----------------

A common use case of TYPO3 extensions is to list, filter and show data. The suggested pattern to do this repeated task described and support by xm_tools consists of two plugins:
one for the query and one for the list. They share the query submitted by the user by using the :doc:`session </sections/tools/session>`.

The query plugin
~~~~~~~~~~~~~~~~

Create a query class that use the :php:class:`QueryTrait` and add the properties you want to filter the data for (see :doc:`here </sections/tools/query>`). Create a controller and a template for
the filter you want to show to your user.

::

    abstract class AbstractController
    {
        /**
         * paginator
         *
         * @var \Xima\XmTools\Classes\Helper\Paginator
         * @inject
         */
        protected $paginator = null;

        /**
         * @param $className
         * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
         */
        protected function initializeQuery($className)
        {
            //the filter object
            $query = $this->session->get('query');
            if (!is_a($query, $className)) {
                $query = $this->objectManager->get($className);
            }

            //paging
            if ($this->request->hasArgument('page')) {
                $query->setCurrentPage($this->request->getArgument('page'));
            }

            //limit
            $query->setLimit($this->settings ['flexform'] ['limit']);

            $this->query = $query;
        }
    }

    class PostQueryController extends AbstractController
    {

        /**
         * action new
         *
         * @return void
         */
        public function newAction()
        {
            $this->initializeQuery('Xima\BlogExampleExtension\Domain\Model\Query\PostQuery');

            //example form data: get tags to allow for filtering on them
            $tags = $this->objectManager->get('Xima\BlogExampleExtension\Domain\Repository\TagRepository')->findAll();
            $this->view->assign('tags', $tags);

            $this->session->set('query', $this->query);
            $this->view->assign('query', $this->query);

        }

        /**
         * action create
         *
         * @param \Xima\BlogExampleExtension\Domain\Model\Query\PostQuery $newPostQuery
         * @return void
         */
        public function createAction(\Xima\XmDwiDb\Domain\Model\Query\PostQuery $newPostQuery)
        {
            $this->session->set('query', $newPostQuery);
            $this->redirect('new');
        }

    }


The list plugin
~~~~~~~~~~~~~~~

In your list action, retrieve the query from the session and let your entity repository filter your data:

::

    class PostController extends AbstractController
    {

        ...

        /**
         * action list
         *
         * @return void
         */
        public function listAction()
        {
            $this->initializeQuery('Xima\BlogExampleExtension\Domain\Model\Query\PostQuery');

            $items = $this->repository->findAllByQuery($query);
            $this->view->assign('items', $items);
        }
    }

Note: The repository function *findAllByQuery* is so far only implemented for the :php:class:`ApiRepository` class (see :php:meth:`ApiRepository::findAllByQuery`).