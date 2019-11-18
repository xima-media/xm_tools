<?php


namespace Xima\XmTools\Database;


/**
 * Class SoftReferenceIndex
 * @package Xima\XmTools\Database
 */
class SoftReferenceIndex extends \TYPO3\CMS\Core\Database\SoftReferenceIndex
{
    /**
     * @param string $table
     * @param string $field
     * @param int $uid
     * @param string $content
     * @param string $spKey
     * @param array $spParams
     * @param string $structurePath
     * @return array|bool
     */
    public function findRef($table, $field, $uid, $content, $spKey, $spParams, $structurePath = '') {
        switch ($spKey) {
            case 'tx_xmtools_flexform_typolink_tag':
                $retVal = $this->findRef_typolink_tag_flexform($content, $spParams);
                break;
            default:
                $retVal = parent::findRef($table, $field, $uid, $content, $spKey, $spParams, $structurePath);
        }

        return $retVal;
    }

    /**
     * @param $content
     * @param $spParams
     * @return array
     */
    public function findRef_typolink_tag_flexform($content, $spParams) {
        // Links in flexform are html_entity encoded.
        // We decode the content here to let the original method find the typolink tags.
        $content = html_entity_decode($content);

        return $this->findRef_typolink_tag($content, $spParams);
    }
}
