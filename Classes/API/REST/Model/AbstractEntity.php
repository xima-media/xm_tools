<?php
namespace Xima\XmTools\Classes\API\REST\Model;

use Xima\XmTools\Classes\Helper\Helper;
/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Wolfram Eberius <wolfram.eberius@xima.de>
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
 * GrowingArea
 */
class AbstractEntity extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * Sets the uid
     *
     * @param  integer $uid
     * @return void
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }
    
    public function parsePropertyArray($array)
    {
        foreach ($array as $key => $value) {
        
            //try to set the value: call a parse function, a setter or set directly
            $parseFunction = 'parse'.Helper::underscoreToCamelCase($key);
            $setter = 'set'.Helper::underscoreToCamelCase($key);
        
            if (method_exists($this, $parseFunction))
            {
                $this->$parseFunction ($value);
            }
            elseif (method_exists($this, $setter))
            {
                $this->$setter ($value);
            } 
            else
            {
               $this->{$key} = $value;
            }
        }
    }
}
