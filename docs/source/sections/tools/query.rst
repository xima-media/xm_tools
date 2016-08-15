Query
-----

xm_tools provides a basic query class which can be used to represent a user's query to the database. The :php:class:`QueryTrait` simply provides common filters and their
*getter* and *setter* functions:

 - *limit*

 - *currentPage*

 - *searchTerm*

 - *lang*

 - *sort*

 - *context* (currently used in connection with the API: different contexts decide about which properties (or just all) of an entity are to be sent back)

In order to set up a query object specific for your domain, create your custom query object and use the :php:class:`QueryTrait` in it:

::

    class PostQuery
    {
        use \Xima\XmTools\Classes\Typo3\Query\QueryTrait
        {
            getParamKeys as traitGetParamKeys;
        }

        /**
         * subject
         *
         * @var string
         */
        protected $subject;

        public function getSubject() {

            return $this->subject;
        }

        public function setSubject($subject) {

            $this->subject = $subject;
            return $this;
        }
    }
