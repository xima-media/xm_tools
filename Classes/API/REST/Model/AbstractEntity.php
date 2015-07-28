<?php

namespace Xima\XmTools\Classes\API\REST\Model;

use Xima\XmTools\Classes\Helper\Helper;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Wolfram Eberius <woe@xima.de>
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
 * Base class for models that get constructed by the \Xima\XmTools\Classes\API\REST\Connector. API data is returned as json, converted to an array and then iterated to instantiate model classes.
 * Model properties are set by each array, a key in the array will become a property name of the model. If the model class has a parse{key} function for a property, then this function will be called instead of
 * setting the data directly.
 *
 * <code>
 class Person extends \Xima\XmTools\Classes\API\REST\Model\AbstractApiEntity
 {
 protected $address;

 public function parseAddress($array)
 {
 $address = new Address();
 $address->setStreet($array['street']);
 $address->setZipCode($array['zipCode']);
 $address->setLatitude($array['latitude']);
 $address->setLongitude($array['longitude']);
 $address->setLongitude($array['longitude']);

 $this->address = $address;
 }
 * </code>
 *
 * @author Wolfram Eberius <woe@xima.de>
 */
class AbstractEntity extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Sets the uid (suggested for the TYPO3 environment).
     *
     * @param int $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @param $array The data to be parsed.
     *
     * Parses array data as $key => $value in the following manner:
     * 1. If a parse function for the $key exists, it's getting called.
     * 2. If a setter function for $key exists, it's getting called.
     * 3. Otherwise, $value will be set to a property called $key.
     */
    public function parsePropertyArray($array)
    {
        foreach ($array as $key => $value) {

            //try to set the value: call a parse function, a setter or set directly
            $parseFunction = 'parse'.Helper::underscoreToCamelCase($key);
            $setter = 'set'.Helper::underscoreToCamelCase($key);

            if (method_exists($this, $parseFunction)) {
                $this->$parseFunction($value);
            } elseif (method_exists($this, $setter)) {
                $this->$setter($value);
            } else {
                $this->{$key} = $value;
            }
        }
    }
}
