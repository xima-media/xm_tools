Overview
--------

xm_tools was built to facilitate common use cases a TYPO3 extension developer runs into. It is an extension for extension developers, so you will not find any frontend
or backend plugin in it. The purpose was to generalize certain workflows and so outsource commonly used source code.

A common requirement for TYPO3 extensions is to :doc:`list and filter data </sections/tools/filter-list-flow>`. Using session data more quickly for developers is
described :doc:`here </sections/tools/session>`. Sometimes we want to access another extension from our extension, such as the other extension's configuration, assets
or translations. You can learn how to do this in the section :doc:`Extension Manager </sections/tools/extension-manager>`.

You can use xm_tools to store :doc:`global translations </sections/tools/I10n>` that you want to use from other extensions as well. Some usefull :doc:`Fluid view helpers </viewhelper>`
are also included. Hooks for existing extensions we sometimes use are covered :doc:`here </sections/tools/extensions>`.

Another part of xm_tools is to help connect an extensions to an API. There is a workflow described :doc:`here </sections/tools/restapi>` which covers the generation of an extension based on the data
structure and how to query it. xm_tools makes it possible to use your entities and repositories the 'extbase way' as if the data was in your TYPO3 database.