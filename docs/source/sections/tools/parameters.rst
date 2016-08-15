Parameters
----------

The meaning of parameters in this extension is the availability of some configuration or other information to all extensions. Imagine you want all your dates formatted
 in the same way, being it called from PHP, Fluid or Javascript. Just copy *parameters.yml.dist* from xm_tools' root folder to *parameters.yml* and fill your data you want to have available anywhere.

The :doc:`Servcies   </sections/tools/services>` class looks for an existing *parameters.yml*, parses (and caches it) and makes it available. If you want to have the parameters
available in Javascript, enable it by configuring xm_tools to do so (see :doc:`Configuration </config>`). This will generate a Javascript file and make it load. Your translations are available in Javascript through:

::

    ...
    xmTools.getParameter('dateFormat');
    ...
