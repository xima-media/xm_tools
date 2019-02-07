<?php

namespace Xima\XmTools\Domain\Repository;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2017 OpenSource Team, XIMA MEDIA GmbH, osdev@xima.de
 *  Inspird by http://blog.systemfehler.net/erweiterung-des-typo3-kategoriensystems/
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class CategoryRepository
 * @package Xima\XmTools\Domain\Repository
 */
class CategoryRepository extends \TYPO3\CMS\Extbase\Domain\Repository\CategoryRepository
{
    protected $defaultOrderings = array('sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING);

    /**
     * Find child categories of a given parent
     *
     * @param int $category
     * @param array $excludeCategories
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findChildrenByParent($category = 0, $excludeCategories = array())
    {
        $constraints = array();
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $constraints[] = $query->equals('parent', $category);

        if (count($excludeCategories) > 0) {
            $constraints[] = $query->logicalNot($query->in('uid', $excludeCategories));
        }

        $query->matching($query->logicalAnd($constraints));

        return $query->execute();
    }

    /**
     * Get categories of a certain record
     *
     * @param string $table Table of the record to which categories are attached
     * @param string $fieldname Filedname where the categories are stored
     * @param int $uid Uid of the record
     * @return array
     */
    public function findByTableAndFieldname(string $table, string $fieldname, int $uid)
    {
        $where = 'AND sys_category_record_mm.fieldname = '
            . $GLOBALS['TYPO3_DB']->fullQuoteStr($fieldname, 'sys_category_record_mm')
            . ' AND sys_category_record_mm.uid_foreign = ' . (int)$uid;

        $where .= ' AND (sys_category.sys_language_uid = ' . (int)$GLOBALS['TSFE']->sys_language_uid
                . ' OR sys_category.sys_language_uid = -1)';

        $res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
            'sys_category.*',
            'sys_category',
            'sys_category_record_mm',
            $table,
            $where,
            '',
            'sys_category_record_mm.sorting_foreign'
        );

        $rows = [];
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }
}
