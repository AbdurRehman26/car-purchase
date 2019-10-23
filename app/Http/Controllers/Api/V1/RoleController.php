<?php

namespace App\Http\Controllers;

use App\Data\Repositories\RoleRepository;
use Kazmi\Http\Controllers\ApiResourceController;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;


class RoleController extends ApiResourceController{
    
    public $_repository;

    public function __construct(RoleRepository $repository){
        $this->_repository = $repository;
    }

    public function rules($value=''){
        $rules = [];

        if($value == 'store'){
            

        }

        if($value == 'update'){

            $rules['id'] =  'required';

        }


        if($value == 'destroy'){

            $rules['id'] =  'required';

        }

        if($value == 'show'){

            $rules['id'] =  'required';

        }

        if($value == 'index'){
         
            $rules['pagination'] =  'nullable|in:true,false';

        }

        return $rules;
    
    }

    public function input($value=''){

        $input = request()->only('id', 'operations', 'title', 'pagination');
        return $input;
    }


    public function store(Request $request)
    {
        $data = $this->input(__FUNCTION__);
        $rules = $this->rules(__FUNCTION__);

        $this->validate($request, $rules);

        $roleData = ['title' => $data['title']];

        $user_id = request()->user() ? request()->user()->id : null;

        $role = $this->_repository->create($roleData);
        
        $data['role_id'] = $role->id;

        $permissionsData = app('PermissionRepository')->composeInputData($data);
        
        app('PermissionRepository')->model->insertOnDuplicateKey($permissionsData);

        $output = ['response' => ['message' => 'Role has been added successfully.']];

        // HTTP_OK = 200;

        return response()->json($output, Response::HTTP_OK);

    }


}