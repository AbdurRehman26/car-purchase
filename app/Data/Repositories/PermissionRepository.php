<?php

namespace App\Data\Repositories;

use Kazmi\Data\Contracts\RepositoryContract;
use Kazmi\Data\Repositories\AbstractRepository;
use App\Data\Models\Permission;
use Carbon\Carbon;

class PermissionRepository extends AbstractRepository implements RepositoryContract
{
    /**
     *
     * These will hold the instance of Permission Class.
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
     * Example: Permission-1
     *
     * @var string
     * @access protected
     *
     **/

    protected $_cacheKey = 'Permission';
    protected $_cacheTotalKey = 'total-Permission';

    public function __construct(Permission $model)
    {
        $this->model = $model;
        $this->builder = $model;

    }

    public function findCollectionByCriteria($criteria)
    {
        $this->builder = $this->model->where($criteria);
        return parent::findByAll();
    }

    public function composeInputData($data)
    {
        $input = [];
        $now = Carbon::now()->toDateTimeString();

        foreach ($data['operations'] as $key => $operation_id) {

            $input[] = ['operation_id' => $operation_id , 'role_id' => $data['role_id'] , 'created_at' => $now , 'updated_at' => $now , 'deleted_at' => null];
        }

        return $input;
    }

    
    public function getAttributesByCriteriaWithGroupBy($criteria , $attributes , $groupBy)
    {
        $ids = $this->model->where($criteria)->groupBy($groupBy)->get()->pluck($attributes);

        $workspaces = [];

        foreach ($ids as  $id) {

            $criteria = ['role_id' => $criteria['role_id']];

            $permissions = $this->model->where($criteria)->get()->pluck(['operation_id']);
            
            foreach($permissions as $key=>$permission){
                $permissions[$key] = 'op-'.$permissions[$key];
            }


            $workspace = ['id' => $id , 'selectedOperations' => $permissions];

            $workspaces[] = $workspace;
        }

        return $workspaces;
    }



    public function getAttributesByCriteria($criteria , $attributes)
    {   
        $ids = $this->model->where($criteria)->get()->pluck($attributes);
        foreach ($ids as $key => $id) {
            $ids[$key] = 'op-'.$id;
        }
        return $ids;
    }



    public function deleteByCriteria($criteria , $existingOptionIds = [])
    {
        $this->model->where($criteria)->whereNotIn('operation_id' , $existingOptionIds)->delete();

        \Cache::flush();

        return true;
    }

    public function composeGeneralOperations($data)
    {
        $input = [];
        $now = Carbon::now()->toDateTimeString();
        if(!empty($data['general_operations'])){

            foreach ($data['general_operations'] as $operation_id) {        

                if(!strpos($operation_id, 'p-')){
                    $criteria = ['entity_id' => $operation_id];
                    $childOperations = app('OperationRepository')->findCollectionByCriteria($criteria);

                    foreach ($childOperations['data'] as $key => $childOperation) {

                        $input[] = [ 'operation_id' => str_replace('op-', '', $childOperation->id) , 'role_id' => $data['role_id'] , 'created_at' => $now , 'updated_at' => $now , 'deleted_at' => null];
                    }
                    continue;
                }

                $input[] = [ 'operation_id' => str_replace('op-', '', $operation_id) , 'role_id' => $data['role_id'] , 'created_at' => $now , 'updated_at' => $now , 'deleted_at' => null];

            }
        }
        return $input;
    }


    public function deleteByWhereInAndCriteria($criteria, $whereInType, $whereInIds)
    {

        $this->model->where($criteria)->whereNotNull($whereInType)->whereNotIn($whereInType , $whereInIds)->delete();
        $this->model->where($criteria)->delete();

        \Cache::flush();

        return true;
    }


}
