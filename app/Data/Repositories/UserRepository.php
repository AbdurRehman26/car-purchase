<?php

namespace App\Data\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Kazmi\Data\Contracts\RepositoryContract;
use Kazmi\Data\Repositories\AbstractRepository;
use App\Data\Models\User;

class UserRepository extends AbstractRepository implements RepositoryContract
{
    /**
     *
     * These will hold the instance of User Class.
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
     * Example: User-1
     *
     * @var string
     * @access protected
     *
     **/

    protected $_cacheKey = 'User';
    protected $_cacheTotalKey = 'total-User';

    public function __construct(User $model)
    {
        $this->model = $model;
        $this->builder = $model;

    }

    /**
     *
     * This method will create a new model
     * and will return output back to client as json
     *
     * @access public
     * @return mixed
     *
     * @author Usaama Effendi <usaamaeffendi@gmail.com>
     *
     **/

    public function create(array $data = []) {

        $data['roles'] = ['admin'];
        $data['password'] = bcrypt('admin123!@#');

        return parent::create($data);
    }


    public function findById($id, $refresh = false, $details = false, $encode = true) {

        $data = parent::findById($id, $refresh, $details, $encode);

        if(!empty($data->role_id)){
            $data->role = app('RoleRepository')->findById($data->role_id);
        }

        return $data;

    }


}
