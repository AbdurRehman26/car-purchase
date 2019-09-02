<?php

namespace App\Data\Repositories;

use Kazmi\Data\Contracts\RepositoryContract;
use Kazmi\Data\Repositories\AbstractRepository;
use App\Data\Models\Purchase;

class PurchaseRepository extends AbstractRepository implements RepositoryContract
{
    /**
     *
     * These will hold the instance of Purchase Class.
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
     * Example: Purchase-1
     *
     * @var string
     * @access protected
     *
     **/

    protected $_cacheKey = 'Purchase';
    protected $_cacheTotalKey = 'total-Purchase';

    public function __construct(Purchase $model)
    {
        $this->model = $model;
        $this->builder = $model;

    }

    public function insertMultiple($input)
    {
        $created_at = \Carbon\Carbon::now()->toDateTimeString();
        # code...
        
        foreach ($input as $key => $row) {

            $row['created_at'] = $created_at;
            $row['updated_at'] = $created_at;
            
            $this->create($row);

        }

        return true;
    }

}
