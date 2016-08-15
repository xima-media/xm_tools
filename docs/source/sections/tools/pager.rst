Paging
------

The :php:class:`Paginator` generates links to walk through a paged result set. It returns an array of links (see :php:meth:`Paginator::getPageBrowser`).

::

    $paginator = new \Xima\XmTools\Classes\Helper\Paginator();
    $pageBrowser = $paginator->getPageBrowser(
            $countAllResult,
            $countItemsPerPage,
            $currentPage,
            $url
        );

    $this->view->assign('pageBrowser', $pageBrowser);

A template to render the returned array is not yet part of xm_tools. An example template would look like:

::

    {namespace xmTools = Xima\XmTools\Classes\ViewHelpers}

    <f:if condition="{pageBrowser.countPages}">
        <nav class="pager-nav nav-js">
            <ul class="pagination">
                <f:if condition="{pageBrowser.prev}">
                    <li>
                        <f:link.action arguments="{page: pageBrowser.prev, tab: tab}" title="{dict.list_pager_previous_page}">
                            <span class="icon icon-backward"></span>
                        </f:link.action>
                    </li>
                </f:if>

                <f:if condition="{xmTools:object.ArrayCheck(array:pageBrowser.pages,needle:1,check:'NOT_IN_KEYS')}">
                    <li>
                        <f:link.action arguments="{page: 1, tab: tab}">
                            1
                        </f:link.action>
                    </li>
                    <f:if condition="{xmTools:object.ArrayCheck(array:pageBrowser.pages,needle:2,check:'NOT_IN_KEYS')}">
                        <li>
                            ...
                        </li>
                    </f:if>
                </f:if>

                <f:for each="{pageBrowser.pages}" as="page" key="n">
                    <f:if condition="{page.current} == 0">
                        <f:then>
                            <li>
                                <f:link.action arguments="{page: n, tab: tab}">
                                    {n}
                                </f:link.action>
                            </li>
                        </f:then>
                        <f:else>
                            <li class="active">
                                <a>{n}</a>
                            </li>
                        </f:else>
                    </f:if>
                </f:for>

                <f:if condition="{xmTools:object.ArrayCheck(array:pageBrowser.pages,needle:pageBrowser.countPages,check:'NOT_IN_KEYS')}">
                    <f:if condition="{xmTools:object.ArrayCheck(array:pageBrowser.pages,needle:pageBrowser.penUltimatePage,check:'NOT_IN_KEYS')}">
                        <li>
                            ...
                        </li>
                    </f:if>
                    <li>
                        <f:link.action arguments="{page: pageBrowser.countPages, tab: tab}">
                            {pageBrowser.countPages}
                        </f:link.action>
                    </li>

                </f:if>

                <f:if condition="{pageBrowser.next}">
                    <li>
                        <f:link.action arguments="{page: pageBrowser.next, tab: tab}" title="{dict.list_pager_next_page}">
                            <span class="icon icon-forward"></span>
                        </f:link.action>
                    </li>
                </f:if>
            </ul>
        </nav>
    </f:if>