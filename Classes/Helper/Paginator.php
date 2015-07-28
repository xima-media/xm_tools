<?php

namespace Xima\XmTools\Classes\Helper;

use Xima\XmTools\Classes\Typo3\Helper\Localization;

/**
 * @author Steve Lenz <sle@xima.de>
 *
 * @todo Refactor
 */
class Paginator
{
    /**
     * @param int    $count
     * @param int    $limit
     * @param int    $currentPage
     * @param string $url
     * @param int    $showLinksForBrowse
     *
     * @return Ambigous <unknown, multitype:, boolean, multitype:number >
     */
    public function getPageBrowser($count, $limit, $currentPage, $url, $showLinksForBrowse = 4)
    {
        $browser = $this->create($count, $limit, $currentPage, $showLinksForBrowse);
        if ($browser) {
            $browser ['totalResults'] = $count;
            if ($url) {
                $browser['pageUrl'] = $url;
            }
        }

        return $browser;
    }

    /**
     * @param int $countItems
     * @param int $itemsPerPage
     * @param int $currentPage
     * @param int $showLinksForBrowse
     *
     * @return array
     */
    private function create($countItems, $itemsPerPage, $currentPage, $showLinksForBrowse)
    {
        $pager = array();
        if ($countItems <= $itemsPerPage) {
            $pager ['pages'] [1] ['current'] = true;
        } else {
            // get the number of pages
            $countPages = (int) ceil($countItems / $itemsPerPage);

            // links for browser
            if ($countPages < $showLinksForBrowse) {
                $itemsBeforAfterCurrentPage = (int) ceil(($countPages / 2));
            } else {
                $itemsBeforAfterCurrentPage = (int) ceil(($showLinksForBrowse / 2));
            }

            // get start and end
            $nextToLast = $countPages - 1;
            switch ($currentPage) {
                case 1 :
                    // first
                    $start = 1;
                    if (($itemsBeforAfterCurrentPage + $itemsBeforAfterCurrentPage) > $countPages) {
                        $end = $countPages;
                    } else {
                        $end = $itemsBeforAfterCurrentPage + $itemsBeforAfterCurrentPage;
                    }
                    if ($countPages > $showLinksForBrowse) {
                        $end = $end + 1;
                    }
                    break;
                case 2 :
                    // first
                    $start = 1;
                    if (($itemsBeforAfterCurrentPage + $itemsBeforAfterCurrentPage) > $countPages) {
                        $end = $countPages;
                    } else {
                        $end = $itemsBeforAfterCurrentPage + $itemsBeforAfterCurrentPage;
                    }
                    if ($countPages > $showLinksForBrowse) {
                        $end = $end + 1;
                    }
                    break;
                case $countPages :
                    // last
                    $start = (($currentPage - $itemsBeforAfterCurrentPage - $itemsBeforAfterCurrentPage - 1) <= 0) ? 1 : $currentPage - $itemsBeforAfterCurrentPage - $itemsBeforAfterCurrentPage;
                    $end = $currentPage;
                    break;
                case $nextToLast :
                    // next to last
                    $start = (($currentPage - $itemsBeforAfterCurrentPage - 1) <= 0) ? 1 : $currentPage - $itemsBeforAfterCurrentPage - 1;
                    $end = $countPages;
                    break;
                default :
                    $start = (($currentPage - $itemsBeforAfterCurrentPage) <= 0) ? 1 : $currentPage - $itemsBeforAfterCurrentPage;
                    $end = (($currentPage + $itemsBeforAfterCurrentPage) > $countPages) ? $countPages : $currentPage + $itemsBeforAfterCurrentPage;
            } // switch

            /*
             * build pagination
             */
            // first
            if ($currentPage > 1) {
                $pager ['first'] = 1;
            }
            // predecessor
            if ($currentPage > 1) {
                $pager ['prev'] = $currentPage - 1;
            }
            // pages
            for ($i = $start; $i <= $end; $i++) {
                $pager ['pages'] [$i] ['current'] = ($i == $currentPage) ? true : false;
                $pager ['pages'] [$i] ['notFirstItem'] = ($i != $start) ? true : false;
            }

            // next
            if ($currentPage < $countPages) {
                $pager ['next'] = $currentPage + 1;
            }
            // last
            if ($currentPage < $countPages) {
                $pager ['last'] = $countPages;
            }

            // total number of pages
            $pager ['countPages'] = $countPages;
            $pager ['penUltimatePage'] = $countPages - 1;
        }

        //get the display text for the result information
        //todo: place this somewhere else or do some refactoring
        $resultLabel = '';
        $countLabel = '';
        $dictionary = Localization::getDictionary();

        if ($countItems == 1) {
            $resultLabel = $dictionary->getList_1_result();
            $countLabel = $resultLabel;
        } elseif (1 == count($pager['pages'])) {
            $resultLabel = sprintf($dictionary->getList_x_results(), $countItems);
            $countLabel = $resultLabel;
        } else {
            $currentFirst = ($currentPage - 1) * $itemsPerPage + 1;
            $currentLast = min(($currentPage - 1) * $itemsPerPage + $itemsPerPage, $countItems);
            $resultLabel = sprintf($dictionary->getList_x_results_of_y(), $currentFirst, $currentLast, $countItems);
            $countLabel = sprintf($dictionary->getList_x_results(), $countItems);
        }

        $pager['resultLabel'] = $resultLabel;
        $pager['countLabel'] = $countLabel;

        return $pager;
    }
}
