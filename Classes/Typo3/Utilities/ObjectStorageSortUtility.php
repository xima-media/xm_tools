<?php

namespace Xima\XmTools\Typo3\Utilities;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class ObjectStorageSortUtility
 *
 * @author Steve Lenz <steve.lenz@xima.de> XIMA MEDIA GmbH
 * @package Xima\XmTools\Typo3
 */
class ObjectStorageSortUtility
{

    /**
     * @var String
     */
    protected static $property;

    /**
     * Sorts objects by given property
     *
     * @param $objectStorage
     * @param $property
     * @return bool|\TYPO3\CMS\Extbase\Persistence\ObjectStorage - Result or false if sorting fails
     */
    public static function sortByProperty(ObjectStorage $objectStorage, $property)
    {
        self::$property = 'get' . ucfirst(strtolower($property));

        $array = $objectStorage->toArray();
        $sample = $array[0];

        if (method_exists($sample, self::$property)) {
            usort($array, array(self, 'compare'));
        } else {
            return false;
        }

        $sorted = new ObjectStorage();

        foreach ($array as $item) {
            $sorted->attach($item);
        }

        return $sorted;
    }

    /**
     * @param $apple object
     * @param $orange object
     * @return int
     */
    protected static function compare($apple, $orange)
    {
        return strcoll($apple->{self::$property}(), $orange->{self::$property}());
    }

}