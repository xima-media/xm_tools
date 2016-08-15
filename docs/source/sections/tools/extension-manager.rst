Extension manager
-----------------

The xm_tools extension offers to access other extensions, meaning their settings, configuration, assets or translations. Get an extension by injecting the :php:class:`ExtensionManager`
and calling it's method :php:meth:`ExtensionManager::getExtensionByName`:

::

    class PostController
    {
        /**
         * @var \Xima\XmTools\Classes\Typo3\Extension\ExtensionManager
         * @inject
         */
        protected $extensionManager;

        public function exampleAction()
        {
            $someOtherExtension = $this->extensionManager->getExtensionByName('SomeOtherExtensionName');
        }
    }

This will give you a :php:class:`Extension` object.