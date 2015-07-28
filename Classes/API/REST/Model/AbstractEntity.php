<?php

namespace Xima\XmTools\Classes\API\REST\Model;

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
 * AbstractEntity.
 *
 * Base class for models that get constructed by the \Xima\XmTools\Classes\API\REST\Connector. API data is returned as json, converted to an array and then iterated to instantiate model classes.
 * Objects get hydrated by netresearch/jsonmapper.
 * @todo Store both id and ui?
 */
class AbstractEntity extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * Hook to perform actions after json mapping.
     */
    public function postMapping()
    {
        $this->uid = $this->id;
    }

    /**
     * Sets the uid (suggested for the TYPO3 environment).
     *
     * @param int $uid
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
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
