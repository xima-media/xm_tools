<?php

namespace Xima\XmTools\ViewHelpers;

use TYPO3\CMS\Fluid\ViewHelpers\Form\CheckboxViewHelper;

/* * *************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Steve Lenz <steve.lenz@xima.de>, XIMA MEDIA GmbH
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
 * ************************************************************* */

class ExtendedCheckboxViewHelper extends CheckboxViewHelper
{

    /**
     * @var string
     */
    protected $tagName = 'input';

    /**
     * Initialize the arguments.
     *
     * @return void
     * @api
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
    }

    /**
     * Renders the checkbox.
     *
     * @param boolean $checked Specifies that the input element should be preselected
     * @param boolean $multiple Specifies whether  this checkbox belongs to a multivalue (is part of a checkbox group)
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     * @return string
     * @api
     */
    public function render($checked = NULL, $multiple = NULL)
    {
        $this->tag->addAttribute('type', 'checkbox');

        $nameAttribute  = $this->getName();
        $valueAttribute = $this->getValue();

        if ($this->isObjectAccessorMode()) {
            if ($this->hasMappingErrorOccurred()) {
                $propertyValue = $this->getLastSubmittedFormData();
            } else {
                $propertyValue = $this->getPropertyValue();
            }
            if ($propertyValue instanceof \Traversable) {
                $propertyValue = iterator_to_array($propertyValue);
            }

            if (is_array($propertyValue)) {
                if (0 < count($propertyValue) &&
                        is_object(current($propertyValue)) &&
                        method_exists(current($propertyValue), 'getUid')) {
                    foreach ($propertyValue as $propertyValueItem) {
                        if ($valueAttribute == $propertyValueItem->getUid()) {
                            $checked = TRUE;
                            continue;
                        }
                    }
                } else {
                    if ($checked === NULL) {
                        $checked = in_array($valueAttribute, $propertyValue);
                    }
                }

                $nameAttribute .= '[]';
            } elseif ($multiple === TRUE) {
                $nameAttribute .= '[]';
            } elseif ($checked === NULL && $propertyValue !== NULL) {
                $checked = (bool) $propertyValue === (bool) $valueAttribute;
            }
        }

        $this->registerFieldNameForFormTokenGeneration($nameAttribute);
        $this->tag->addAttribute('name', $nameAttribute);
        $this->tag->addAttribute('value', $valueAttribute);
        if ($checked) {
            $this->tag->addAttribute('checked', 'checked');
        }
        $this->setErrorClassAttribute();
        $hiddenField = $this->renderHiddenFieldForEmptyValue();

        return $hiddenField . $this->tag->render();
    }

}
