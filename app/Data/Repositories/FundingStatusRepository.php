<?php

namespace App\Data\Repositories;

use Kazmi\Data\Contracts\RepositoryContract;
use Kazmi\Data\Repositories\AbstractRepository;
use App\Data\Models\FundingStatus;

class FundingStatusRepository extends AbstractRepository implements RepositoryContract
{
    /**
     *
     * These will hold the instance of FundingStatus Class.
     *
     * @var object
     * @access public
     *
     **/
    public $model;

    /**
     *
     * This is the prefix of the cache key to which the
     * App\Data\Repositories data will be stored
     * App\Data\Repositories Auto incremented Id will be append to it
     *
     * Example: FundingStatus-1
     *
     * @var string
     * @access protected
     *
     **/

    protected $_cacheKey = 'FundingStatus';
    protected $_cacheTotalKey = 'total-FundingStatus';

    public function __construct(FundingStatus $model)
    {
        $this->model = $model;
        $this->builder = $model;

    }
}
