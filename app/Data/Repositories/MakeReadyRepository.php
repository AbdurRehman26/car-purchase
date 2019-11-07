<?php

namespace App\Data\Repositories;

use Kazmi\Data\Contracts\RepositoryContract;
use Kazmi\Data\Repositories\AbstractRepository;
use App\Data\Models\MakeReady;

class MakeReadyRepository extends AbstractRepository implements RepositoryContract
{
    /**
     *
     * These will hold the instance of MakeReady Class.
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
     * Example: MakeReady-1
     *
     * @var string
     * @access protected
     *
     **/

    protected $_cacheKey = 'MakeReady';
    protected $_cacheTotalKey = 'total-MakeReady';

    public function __construct(MakeReady $model)
    {
        $this->model = $model;
        $this->builder = $model;

    }
}
