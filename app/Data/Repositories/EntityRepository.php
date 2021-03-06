<?php

namespace App\Data\Repositories;

use Kazmi\Data\Contracts\RepositoryContract;
use Kazmi\Data\Repositories\AbstractRepository;
use App\Data\Models\Entity;

class EntityRepository extends AbstractRepository implements RepositoryContract
{
    /**
     *
     * These will hold the instance of Entity Class.
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
     * Example: Entity-1
     *
     * @var string
     * @access protected
     *
     **/

    protected $_cacheKey = 'Entity';
    protected $_cacheTotalKey = 'total-Entity';

    public function __construct(Entity $model)
    {
        $this->model = $model;
        $this->builder = $model;

    }
}
