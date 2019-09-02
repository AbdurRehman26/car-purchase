<?php

namespace App\Http\Controllers\Api\V1;

use App\Data\Repositories\FileExportRepository;
use Kazmi\Http\Controllers\ApiResourceController;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Jobs\ImportCarData;


class FileExportController extends ApiResourceController{
    
    public $_repository;

    public function __construct(FileExportRepository $repository){
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

        $input = request()->only('id', 'file', 'test');

        $input['file'] = request()->file('file');
        
        return $input;
    }


    public function store(Request $request)
    {
        $input = $this->input(__FUNCTION__);
        $rules = $this->rules(__FUNCTION__);

        $this->validate($request, $rules);

        $tempFileName = $input['file']->storePublicly('');

        ImportCarData::dispatch($tempFileName)->onQueue(config('queue.prefix') . 'case-update');

        $user_id = request()->user() ? request()->user()->id : null;

        $output = ['message' => 'File Imported Successfully'];

        // HTTP_OK = 200;

        return response()->json($output, Response::HTTP_OK);

    }




}