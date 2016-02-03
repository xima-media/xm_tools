<?php

namespace Xima\XmTools\Classes\API\REST\Model;

/**
 * Model class for sortables retrieved by the SortableRepository.
 *
 * @author Wolfram Eberius <woe@xima.de>
 */
class Sortable extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Sets the uid.
     *
     * @param int $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }
}
