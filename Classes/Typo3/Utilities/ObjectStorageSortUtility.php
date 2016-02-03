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
    protected $property;

    /**
     * Sorts objects by given property
     *
     * @param $objectStorage
     * @param $property
     * @return bool|\TYPO3\CMS\Extbase\Persistence\ObjectStorage - Result or false if sorting fails
     */
    public function sortByProperty(ObjectStorage $objectStorage, $property)
    {
        $this->property = 'get' . ucfirst(strtolower($property));

        $array = $objectStorage->toArray();
        $sample = $array[0];

        if (method_exists($sample, $this->property)) {
            usort($array, array($this, 'compare'));
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
    private function compare($apple, $orange)
    {
        return strcoll($apple->{$this->property}(), $orange->{$this->property}());
    }

}