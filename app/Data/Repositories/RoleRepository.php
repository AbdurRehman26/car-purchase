<?php

namespace App\Data\Repositories;

use Kazmi\Data\Contracts\RepositoryContract;
use Kazmi\Data\Repositories\AbstractRepository;
use App\Data\Models\Role;

class RoleRepository extends AbstractRepository implements RepositoryContract
{
    /**
     *
     * These will hold the instance of Role Class.
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
     * Example: Role-1
     *
     * @var string
     * @access protected
     *
     **/

    protected $_cacheKey = 'Role';
    protected $_cacheTotalKey = 'total-Role';

    public function __construct(Role $model)
    {
        $this->model = $model;
        $this->builder = $model;

    }

        /**
     *
     * This method will fetch single model
     * and will return output back to client as json
     *
     * @access public
     * @return mixed
     *
     * @author Usaama Effendi <usaamaeffendi@gmail.com>
     *
     **/
    public function findById($id, $refresh = false, $details = false, $encode = true){

        $role = parent::findById($id , $refresh , $details , $encode);

        $role->permissions = $this->getRolesWithPermissions($role->id);


        return $role;
    }


    public function getRolesWithPermissions($role_id)
    {

            $roles = \DB::table('roles')
                    ->join('permissions', 'roles.id', '=', 'permissions.role_id')
                    ->join('operations', 'operations.id', '=', 'permissions.operation_id')
                    ->select(['permissions.operation_id' , 'roles.title' , 'operations.title'])
                    ->where('permissions.role_id' , $role_id)
                    ->get();

            return $roles;

    }



}
