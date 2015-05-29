<?php

namespace Xima\XmTools\Classes\Extensions\KeSearch\Hooks;

use Xima\XmTools\Classes\Extensions\KeSearch\VO\IndexEntry;
use Xima\XmTools\Classes\Helper\Helper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmTools\Classes\Extensions\KeSearch\VO\IndexLog;

/**
 * Hooks for ke_search.
 *
 * @author Wolfram Eberius
 */
abstract class AbstractIndexer
{
    /**
     * objectManager.
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    public function __construct()
    {
        $this->objectManager = GeneralUtility::makeInstance('TYPO3\CMS\\Extbase\Object\ObjectManager');
    }

    abstract protected function getType();

    abstract protected function getName();

    public function registerIndexerConfiguration(&$params, $pObj)
    {
        $services = $this->objectManager->get('Xima\XmTools\Classes\Typo3\Services');
        /* @var $services \Xima\XmTools\Classes\Typo3\Services */

        $extension = $services->getExtensionManager()->getExtensionByName(Helper::getClassPackageName($this));
        // add item to "type" field
        $newArray = array(
                $this->getName(),
                $this->getType(),
                '..'.DIRECTORY_SEPARATOR.$extension->getRelPath().'ext_icon.gif',
        );
        $params ['items'] [] = $newArray;

        // enable "sysfolder" field
        $GLOBALS ['TCA'] ['tx_kesearch_indexerconfig'] ['columns'] ['sysfolder'] ['displayCond'] .= ','.$this->getType();
    }

    protected function storeInIndex(IndexEntry $indexEntry, \tx_kesearch_indexer $indexerObject, $indexerConfig)
    {
        $indexerObject->amountOfRecordsToSaveInMem = 999999;

        return $indexerObject->storeInIndex($indexerConfig ['storagepid'],         // storage PID
            $indexEntry->getTitle(),         // record title
            $indexerConfig ['type'],         // content type
            $indexerConfig ['targetpid'],         // target PID: where is the single view?
            $indexEntry->getContent(),         // indexed content, includes the title (linebreak after title)
            $indexEntry->getTags(),         // tags for faceted search
            $indexEntry->getParams(),         // typolink params for singleview
            $indexEntry->getAbstract(),         // abstract; shown in result list if not empty
            $indexEntry->getLanguageUid(),         // language uid
            '',         // starttime
            '',         // endtime
            '',         // fe_group
            false,         // debug only?
            $indexEntry->getAdditionalFields()); // additionalFields
    }

    protected function getReport($indexerConfig, $objects, IndexLog $indexLog = null)
    {
        $count = count($objects);
        $count .= ($indexLog && $indexLog->getCountTotal() > $count) ? ' of total '.$indexLog->getCountTotal() : '';

        return '<p><b>Indexer "'.$indexerConfig ['title'].'":</b></br> '.$count.' elements have been indexed.</b></p>';
    }
}
