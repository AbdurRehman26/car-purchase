<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	$operations = app('OperationRepository')->findByAll(false, 10, []);
    
    	$operationIds = [];

    	foreach ($operations['data'] as $key => $operation) {

    		$operationIds[] = $operation->id;
    	
    	}


    	if($operations['data']){

    		$roleData = ['title' => 'Admin'];

			$role = app('RoleRepository')->create($roleData);

			$data['operations'] = $operationIds;
			$data['role_id'] = $role->id;

			$permissionsData = app('PermissionRepository')->composeInputData($data);

        	app('PermissionRepository')->model->insertOnDuplicateKey($permissionsData);

    	}

    }
}
