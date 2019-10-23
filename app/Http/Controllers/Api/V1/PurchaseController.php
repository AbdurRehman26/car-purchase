<?php

namespace App\Http\Controllers\Api\V1;

use App\Data\Repositories\PurchaseRepository;
use Kazmi\Http\Controllers\ApiResourceController;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;


class PurchaseController extends ApiResourceController{
    
    public $_repository;

    public function __construct(PurchaseRepository $repository){
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
        
        $input = request()->only('id', 'user_id', 'pagination', 'keyword', 'cash_finance', 'deposit', 'down_payment', 'funding_status', 'inspection', 'lender', 'local_or_state', 'location', 'make_ready', 'parts_needed', 'repair_status', 'shipped', 'signed_record', 'trade_in', 'warranty', 'need_to_address', 'is_sold');
        return $input;
    
    }
}