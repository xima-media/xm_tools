<?php
namespace Xima\XmTools\Classes\API\REST\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Model class for sortables retrieved by the SortableRepository.
 *
 * @package xm_tools
 * @author Wolfram Eberius <woe@xima.de>
 *
 */
class Sortable extends AbstractEntity
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
}
