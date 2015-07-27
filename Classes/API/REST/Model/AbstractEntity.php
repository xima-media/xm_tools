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
 * AbstractEntity
 * 
 * @todo Store both id and ui?
 */
class AbstractEntity extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * @var integer
     */
    protected $id;
    
    
    /**
     * Hook to perform actions after json mapping.
     */
    public function postMapping(){
        $this->uid = $this->id;
    }
    
    /**
     * @return void
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }
    
    public function getId()
    {
       return $this->id;
    }
    
    /**
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
}