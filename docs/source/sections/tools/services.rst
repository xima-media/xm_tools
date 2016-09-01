Services
--------

The :php:class:`Services` class can be seen as a facade that you can inject to your controller (or by extending the :php:class:`AbstractController`).
Once initialized, there is a collection of usefull functions you can use:

- :php:meth:`Services::getLang`: get the current language

- :php:meth:`Services::getExtension`: get the current extension (the one you are currently developing)

- :php:meth:`Services::getParameters`: get the system wide parameters (see :doc:`here </sections/tools/parameters>`)

- :php:meth:`Services::addFlexforms`: convenient function to add flexforms to a plugin

- ...

For a full list see the `API documentation <../../_static/api/classes/Xima.XmTools.Classes.Typo3.Services.html>`_.

