<?php
namespace Xima\XmTools\Classes\Typo3\Domain\Repository;

class Repository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * Finds entities by a set of criteria.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array The objects.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        //to do
    }
    
    /**
     * @param \Xima\XmTools\Classes\Typo3\Query\QueryInterface $query
     */
    public function findAllByQuery (\Xima\XmTools\Classes\Typo3\Query\QueryInterface $query)
    {
        //to do
    }
}