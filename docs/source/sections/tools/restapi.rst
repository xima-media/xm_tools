REST-API Connector
------------------

This section describes how to create a TYPO3 extension that retrieves data from and sends data to an API, while acting as an Extbase extension on the TYPO3 side.

Given there is an API that publishes data. We want our extension to have entity classes mapping to the structure the API returns. By using the tool
`Extbaser <https://github.com/edrush/extbaser>`_, you can set up your new extension's skeleton, the file *ExtensionBuilder.json*. Using this and the
TYPO3 backend extension `Extension Builder <https://typo3.org/extensions/repository/view/extension_builder>`_ you can create your extension.

The following image describes the way to create your API extension and how data flows.

.. image:: ../../_static/images/api_workflow.png
   :target: ../../_static/images/api_workflow.png

Enabling the new entities' repositories to retrieve data from the API requires the following steps:

- configure your API connection in the Typoscript template of your plugin or in the extension settings (*ext_conf_template*, then leave the *settings* out):

::

    ...
    settings {
	   api {
            # cat=plugin.tx_yourextension/api/001; type=string; label=Api-URL
            url =
            # cat=plugin.tx_yourextension/api/002; type=string; label=Api-Key
            key =
            # cat=plugin.tx_yourextension/api/003; type=string; label=Api-Schema (available placeholders: [Api-URL], [Api-Route], [Api-Key])
            schema = #e.g.  "[Api-URL]/[Api-Route]?api_key=[Api-Key]"
            # cat=plugin.tx_yourextension/api/004; type=string; label=Route for finding one result by id (available placeholders: [Target])
            routeFindById =
            # cat=plugin.tx_yourextension/api/005; type=string; label=Route for finding by query (available placeholders: [Target])
            routeFindByQuery =
            # cat=plugin.tx_yourextension/api/006; type=string; label=Route for creating entities (available placeholders: [Target])
            routeCreate =
            # cat=plugin.tx_yourextension/api/007; type=string; label=Route for updating entities (available placeholders: [Target])
            routeUpdate =
            # cat=plugin.tx_yourextension/api/008; type=boolean; label=Use Api-Cache
            isCacheEnabled =
        }
    ...
    

- make your model classes extend *\\Xima\\XmTools\\Classes\\API\\REST\\Model\\AbstractEntity*
- make your repositories extend *\\Xima\\XmTools\\Classes\\API\\REST\\Repository\\ApiRepository* and implement *\\Xima\\XmTools\\Classes\\Typo3\\Extension\\ExtensionAwareInterface*

You can then use your new extension's repositories just the same way as native Extbase repositories, e.g.:

::

    ...
    $repository = $objectManager->get('\Xima\BlogExampleExtension\Domain\Repository\CountryRepository');
    $countries = $repository->findAll();
    ...

This wil retrieve all country entites from the API (considering the API offers a corresponding route, meaning the demanded entity's name e.g. `http://your.api/country/query?&api_key=your_key`).
The retrieved data gets mapped to your extension's entity class, e.g. `\Xima\BlogExampleExtension\Domain\Model\Country`.
