Templates
============

**Pake-Link (workaround for internal and external Links in TYPO3 7 < 7.6.3)**

see https://forge.typo3.org/issues/72818

The Partial "Resources/Private/Partials/Link.html" can be used as a workaround to correctly render internal and external links:

.. code-block:: xml

    <v:render.template file="EXT:xm_tools/Resources/Private/Partials/Link.html" variables="{parameter: data.link, linkHtml: linkHtml, class: 'some-classt', title: data.linkText}" />