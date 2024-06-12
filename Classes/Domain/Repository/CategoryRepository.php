<?php

namespace Xima\XmTools\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

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
 */
class CategoryRepository extends Repository
{
    protected $defaultOrderings = ['sorting' => QueryInterface::ORDER_ASCENDING];

    public function initializeObject(): void
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * Find child categories of a given parent
     *
     * @param int $category
     * @param array $excludeCategories
     * @return array|QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findChildrenByParent(int $category = 0, array $excludeCategories = []): QueryResultInterface|array
    {
        $constraints = [];
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $constraints[] = $query->equals('parent', $category);

        if (count($excludeCategories) > 0) {
            $constraints[] = $query->logicalNot($query->in('uid', $excludeCategories));
        }

        $query->matching($query->logicalAnd(...$constraints));

        return $query->execute();
    }
}
