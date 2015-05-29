<?php

namespace Xima\XmTools\Classes\API\REST\Repository;

/**
 * Repository to retrieve a list of sortable values of the API. Set your apiRoute.
 *
 * @author Wolfram Eberius <woe@xima.de>
 */
class SortableRepository extends AbstractApiRepository
{
    /**
     * apiRoute.
     *
     * @var string
     */
    protected $apiRoute = '/attributes?attr=sortables';
}
