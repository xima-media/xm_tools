<?php

namespace TxXmTools\Classes\Helpers;

/**
 * TYPO3 FlexForm Helper
 *
 * @author Sebastian Gierth <sgi@xima.de>
 * @version 0.1.0
 */
class Typo3FlexFormHelper {

    /**
     * Get list of models attribute values from $repository->findAll().
     * Useful to fill $config['items'] of flexform.
     * @param \Tx_Extbase_Persistence_Repository &$repository Repository of domain model.
     * @param array &$config Merges result list into $config['items'] array.
     * @param string $label Optionlabel
     * @param string $value Optionvalue
     */
    public static function buildOptionList(\Tx_Extbase_Persistence_Repository &$repository, &$config, $label, $value)
    {
        $entities   = $repository->findAll();
        $optionList = array();

        $attributes = array(
            'get'.ucfirst(strtolower($label)),
            'get'.ucfirst(strtolower($value)),
        );

        foreach ($entities as $key => $entity) {

            $tmp = array();

            foreach ($attributes as $attr) {
                if (is_callable(array($entity, $attr))){
                    $tmp[] = $entity->{$attr}();
                }
            }

            if ( ! empty($tmp)) $optionList[] = $tmp;
        }

        asort($optionList);

        if (array_key_exists('items', $config) && is_array($config['items'])){
            $config['items'] = array_merge($config['items'], $optionList);
        }
        else {
            $config['items'] = $optionList;
        }
    }
}