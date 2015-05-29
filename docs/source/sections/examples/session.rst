Session handling
================

To write data to and retrieve data from the session, use the :php:class:`SessionManager`:

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
            
            $this->session->write('myData', $data);
        }
    }

This session is kept for the current extension on for the current page. If you want to store data somewhere specific to be able to use it on other pages and/or by another extensions, you can do:

::

    function updateSessionDataForSomewhereElse()
    {
        $data = $this->session->get('myData');
        $date['newStuff'] = 'new text';
        
        //send it to another extension on another page
        $this->session->write('myData', $data, 100, 'anotherExtensionKey');
    }
