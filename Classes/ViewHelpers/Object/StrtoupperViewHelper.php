<?php

namespace Xima\XmTools\Classes\ViewHelpers\Object;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Formats a number with custom precision, decimal point and grouped thousands.
 * @see http://www.php.net/manual/en/function.number-format.php
 *
 * = Examples =
 *
 * <code title="Defaults">
 * <f:format.strtolower>This is an example</f:format.number>
 * </code>
 * <output>
 * this is an example
 * </output>
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 */
class StrtolowerViewHelper extends AbstractViewHelper {

	/**
	 * Format the string with strtolower()
	 *
	 * @return string The formatted string
	 * @author Georg Ringer <typo3@ringerge.org>
	 * @api
	 */
	public function render() {
		return strtolower($this->renderChildren());
	}
}
?>