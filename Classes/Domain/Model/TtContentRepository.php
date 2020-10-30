<?php

namespace Xima\XmTools\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class TtContentRepository
 *
 * @package Xima\XmTools\Domain\Model
 * @deprecated Will be removed in v10. Use Xima\XmTools\Domain\Repository\TtContentRepository instead
 */
class TtContentRepository extends Repository
{

    /**
     *
     */
    public function initializeObject()
    {
        /** @var Typo3QuerySettings $querySettings */
        $querySettings = $this->objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

}
