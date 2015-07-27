<?php

/**
 *
 * @package TYPO3
 * @subpackage Fluid
 * @author Sebastian Gierth <sgi@xima.de>
 *
 * Extends SelectViewHelper of Fluid.
 *
 * @return string
 */
class Tx_XmTools_ViewHelpers_Form_AdvancedSelectViewHelper extends Tx_Fluid_ViewHelpers_Form_SelectViewHelper {

	public function initializeArguments() {

		parent::initializeArguments();

		$this->registerArgument('prependOptionLabel', 'string', 'If specified, will provide an option at first position with the specified label.');
		$this->registerArgument('prependOptionValue', 'string', 'If specified, will provide an option at first position with the specified value.');
	}

	protected function renderOptionTags($options) {

		$output = '';

		if (is_callable(array($this, 'hasArgument')) && $this->hasArgument('prependOptionLabel')) {

			$value = $this->hasArgument('prependOptionValue') ? $this->arguments['prependOptionValue'] : '';
			$label = $this->arguments['prependOptionLabel'];
			$output .= $this->renderOptionTag($value, $label, FALSE) . chr(10);
		}
		else if ($this->arguments->hasArgument('prependOptionLabel')) {

			$value = $this->arguments->hasArgument('prependOptionValue') ? $this->arguments['prependOptionValue'] : '';
			$label = $this->arguments['prependOptionLabel'];
			$output .= $this->renderOptionTag($value, $label, FALSE) . chr(10);
		}

		$output .= parent::renderOptionTags($options);
		return $output;
	}
}

?>