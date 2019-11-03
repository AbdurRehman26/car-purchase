<?php

namespace App\Data\Repositories;

use Kazmi\Data\Contracts\RepositoryContract;
use Kazmi\Data\Repositories\AbstractRepository;
use App\Data\Models\Lender;

class LenderRepository extends AbstractRepository implements RepositoryContract
{
    /**
     *
     * These will hold the instance of Lender Class.
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
     * Example: Lender-1
     *
     * @var string
     * @access protected
     *
     **/

    protected $_cacheKey = 'Lender';
    protected $_cacheTotalKey = 'total-Lender';

    public function __construct(Lender $model)
    {
        $this->model = $model;
        $this->builder = $model;

    }
}
