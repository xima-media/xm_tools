<?php
namespace TxXmTools\Classes\Helpers;

/**
 * Paginator
 * 
 * @author Steve Weinert <kontakt@steve-weinert.de>
 * @copyright (c) 2012, Steve Weinert
 * @version 1.0.0
 */
class Paginator {
    
    /**
		 *
		 * @param int $countItems        	
		 * @param int $itemsPerPage        	
		 * @param int $currentPage        	
		 * @param int $showItemsForBrowse        	
		 * @return array
		 */
    public function create($countItems, $itemsPerPage, $currentPage, $showLinksForBrowse = 4) {
        $pager = array ();
        if ($countItems <= $itemsPerPage) {
            $pager ['pages'] [1] ['current'] = true;
        }
        else {
            // get the number of pages
            $countPages = ( int ) ceil ($countItems / $itemsPerPage);
            
            // links for browser
            if ($countPages < $showLinksForBrowse) {
                $itemsBeforAfterCurrentPage = ( int ) ceil (($countPages / 2));
            }
            else {
                $itemsBeforAfterCurrentPage = ( int ) ceil (($showLinksForBrowse / 2));
            }
            
            // get start and end
            $nextToLast = $countPages - 1;
            switch ($currentPage) {
                case 1 :
                    // first
                    $start = 1;
                    if (($itemsBeforAfterCurrentPage + $itemsBeforAfterCurrentPage) > $countPages) {
                        $end = $countPages;
                    }
                    else {
                        $end = $itemsBeforAfterCurrentPage + $itemsBeforAfterCurrentPage;
                    }
                    if ($countPages > $showLinksForBrowse)
                        $end = $end + 1;
                    break;
                case 2 :
                    // first
                    $start = 1;
                    if (($itemsBeforAfterCurrentPage + $itemsBeforAfterCurrentPage) > $countPages) {
                        $end = $countPages;
                    }
                    else {
                        $end = $itemsBeforAfterCurrentPage + $itemsBeforAfterCurrentPage;
                    }
                    if ($countPages > $showLinksForBrowse)
                        $end = $end + 1;
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
            
            /**
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
            for($i = $start; $i <= $end; $i ++) {
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
        }
        
        return $pager;
    }

    /**
		 * getPageBrowser
		 *
		 * @param
		 *        	$count
		 * @return
		 *
		 */
    public function getPageBrowser($count, $limit, $currentPage, $url) {
        $browser = $this->create ($count, $limit, $currentPage);
        if ($browser) {
            $browser ['totalResults'] = $count;
			if ($url){
				$browser['pageUrl'] = $url;
			}
        }
        
        return $browser;
    }
}

?>