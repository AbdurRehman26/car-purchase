<?php

namespace App\Data\Repositories;

use Kazmi\Data\Contracts\RepositoryContract;
use Kazmi\Data\Repositories\AbstractRepository;
use App\Data\Models\Operation;

class OperationRepository extends AbstractRepository implements RepositoryContract
{
    /**
     *
     * These will hold the instance of Operation Class.
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
     * Example: Operation-1
     *
     * @var string
     * @access protected
     *
     **/

    protected $_cacheKey = 'Operation';
    protected $_cacheTotalKey = 'total-Operation';

    public function __construct(Operation $model)
    {
        $this->model = $model;
        $this->builder = $model;

    }
}
