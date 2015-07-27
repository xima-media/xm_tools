<?php
namespace Xima\XmTools\Classes\API\REST;

class Query extends \TYPO3\CMS\Extbase\Persistence\Generic\Query
{

    protected $repository;

    public function __construct(\Xima\XmTools\Classes\API\REST\Repository\AbstractApiRepository $repository)
    {
        $this->repository = $repository;

        parent::__construct($repository->getObjectType());
    }

    public function execute($returnRawQueryResult = false)
    {
        $params = array();

        //temporary solution: if there is only one column to match
        $params[$this->getConstraint()->getOperand1()->getPropertyName()] = $this->getConstraint()->getOperand2();
        //todo...
        return $this->repository->findAll($params);
    }
}
