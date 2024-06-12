<?php

declare(strict_types=1);

/**
 * Replace config.persistence.classes typoscript configuration
 *
 * https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Breaking-87623-ReplaceConfigpersistenceclassesTyposcriptConfiguration.html
 */
return [
    Xima\XmTools\Domain\Model\Category::class => [
        'tableName' => 'sys_category',
    ],
    Xima\XmTools\Domain\Model\TtContent::class => [
        'tableName' => 'tt_content',
        'properties' => [
            'uid' => [
                'fieldName' => 'uid',
            ],
            'pid' => [
                'fieldName' => 'pid',
            ],
            'sorting' => [
                'fieldName' => 'sorting',
            ],
            'contentType' => [
                'fieldName' => 'CType',
            ],
            'header' => [
                'fieldName' => 'header',
            ],
            'listType' => [
                'fieldName' => 'list_type',
            ],
            'piFlexform' => [
                'fieldName' => 'pi_flexform',
            ],
            'sysLanguageUid' => [
                'fieldName' => 'sys_language_uid',
            ],
        ],
    ],
];
