<?php

namespace Xima\XmTools\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
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
    protected $defaultOrderings = array('sorting' => QueryInterface::ORDER_ASCENDING);

    /**
     * Find child categories of a given parent
     *
     * @param int $category
     * @param array $excludeCategories
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findChildrenByParent($category = 0, $excludeCategories = array()) {
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
}
