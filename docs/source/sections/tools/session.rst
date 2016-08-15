Session handling
----------------

xm_tools provides a convient interface to the wer server's session. To write data to and retrieve data from the session, use the :php:class:`SessionManager`:

::

    class MyController
    {
        /**
         * session
         *
         * @var XmTools\Classes\Typo3\SessionManager
         * @inject
         */
        protected $session;
        
        function updateSessionData()
        {
            $data = $this->session->get('myData');
            $date['newStuff'] = 'new text';
            
            $this->session->set('myData', $data);
        }
    }

This session is kept for the current extension and the current page. If you want to store data somewhere specific to be able to use it on other pages and/or by
another extensions, you can use (see :php:meth:`SessionManager::set`) with key of your choice:

::

    function updateSessionDataForSomewhereElse()
    {
        $data = $this->session->get('myData');
        $date['newStuff'] = 'new text';
        
        //send it to another extension on another page
        $this->session->set('myData', $data, 100, 'anotherExtensionKey');
    }
