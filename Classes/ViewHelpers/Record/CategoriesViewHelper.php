<?php

namespace Xima\XmTools\ViewHelpers\Record;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2019 OpenSource Team, XIMA MEDIA GmbH, osdev@xima.de
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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use Xima\XmTools\Domain\Repository\CategoryRepository;

class CategoriesViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('uid', 'integer', 'UID of the record', true);
        $this->registerArgument('table', 'string', 'Table of the record', true);
        $this->registerArgument('field', 'string', 'Field name which holds the categories in the origin table', false,
            'categories');
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return array|mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $catRepo = $objectManager->get(CategoryRepository::class);

        $categories = $catRepo->findByTableAndFieldname(
            $arguments['table'],
            $arguments['field'],
            $arguments['uid']
        );

        return $categories;
    }
}
