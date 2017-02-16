<?php

namespace Xima\XmTools\Classes\ViewHelpers\Form;

use TYPO3\CMS\Fluid\ViewHelpers\Form\SelectViewHelper;

/**
 * Extends SelectViewHelper of Fluid by providing the arguments to prepend a selectable value at the first position.
 *
 * @author Sebastian Gierth <sgi@xima.de>
 *
 * @return string
 */
class AdvancedSelectViewHelper extends SelectViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('prependOptionLabel', 'string', 'If specified, will provide an option at first position with the specified label.');
        $this->registerArgument('prependOptionValue', 'string', 'If specified, will provide an option at first position with the specified value.');
    }

    protected function renderOptionTags($options)
    {
        $output = '';

        if ($this->hasArgument('prependOptionLabel')) {
            $value = $this->hasArgument('prependOptionValue') ? $this->arguments['prependOptionValue'] : '';
            $label = $this->arguments['prependOptionLabel'];
            $output .= $this->renderOptionTag($value, $label, false).chr(10);
        }

        $output .= parent::renderOptionTags($options);

        return $output;
    }
}