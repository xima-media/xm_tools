<?php

namespace Xima\XmTools\Classes\Extensions\KeSearch\VO;

/**
 * IndexLog.
 */
class IndexLog
{
    /**
     * count
     *
     * @var int
     */
    protected $countTotal = 0;
    
    /**
     * page
     *
     * @var int
     */
    protected $page = 1;
    
    /**
     * languageUid
     *
     * @var int
     */
    protected $languageUid = 0;

    /**
     * lang
     *
     * @var string
     */
    protected $lang;
    
    public function getCountTotal()
    {
        return $this->countTotal;
    }

    public function setCountTotal($countTotal)
    {
        $this->countTotal = $countTotal;
        return $this;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    public function getLanguageUid()
    {
        return $this->languageUid;
    }

    public function setLanguageUid($languageUid)
    {
        $this->languageUid = $languageUid;
        return $this;
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }
 
 
}
