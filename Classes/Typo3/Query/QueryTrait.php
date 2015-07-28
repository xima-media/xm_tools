<?php

namespace Xima\XmTools\Classes\Typo3\Query;

use TYPO3\CMS\Core\Utility\GeneralUtility;

trait QueryTrait
{
    /**
     * currentPage.
     *
     * @var int
     */
    protected $currentPage = 1;

    /**
     * searchTerm.
     *
     * @var string
     */
    protected $searchTerm = null;

    /**
     * sort.
     *
     * @var string
     */
    protected $sort = null;

    /**
     * limit.
     *
     * @var int
     */
    protected $limit = null;

    /**
     * lang.
     *
     * @var string
     */
    protected $lang = null;

    /**
     * context.
     *
     * @var string
     */
    protected $context = null;

    /**
     * params.
     *
     * @var array
     */
    protected $params = array();

    public function getParams()
    {
        $this->params = array();

        if (isset($this->limit) && !empty($this->limit)) {
            $this->params['limit'] = $this->limit;
            $this->params['offset'] = ($this->currentPage - 1) * $this->limit;
        }

        if (isset($this->context)) {
            $this->params['context'] = $this->context;
        }

        if (isset($this->lang)) {
            $this->params['lang'] = $this->lang;
        } else {
            $typo3Services = GeneralUtility::makeInstance("Xima\XmTools\Classes\Typo3\Services");
            $this->params['lang'] = $typo3Services->getLang();
        }

        $paramKeys = $this->getParamKeys();
        foreach ($paramKeys as $key) {
            if (isset($this->$key) && !empty($this->$key)) {
                $this->params[$key] = $this->$key;
            }
        }

        return $this->params;
    }

    protected function getParamKeys()
    {
        return ['sort', 'searchTerm'];
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function getSearchTerm()
    {
        return $this->searchTerm;
    }

    /**
     * @param string $searchTerm
     */
    public function setSearchTerm($searchTerm)
    {
        $this->searchTerm = $searchTerm;

        return $this;
    }

    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }
}
