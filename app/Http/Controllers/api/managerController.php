<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\managerService; 

use App\Http\Requests\addOrder;
use App\Http\Requests\addOutward;
class managerController extends Controller
{
    //manager services 
    protected $managerService;                                        
    public function __construct(managerService $managerService){        
        $this->managerService = $managerService;                         
    }    



     //managerAdd order
     public function addOrder(addOrder $request){
        $response=$this->managerService->addOrder(
            $request->recipe_id,
           
           
        );
        return response()->json($response, $response['status'] == 1 ? 200 : 500);
    }


        //managerAdd outwardquatinty
     public function addOutward(addOutward $request){
        $response=$this->managerService->addOutward(
            $request->item_name,
            $request->outward_quantity
           
           
        );
        return response()->json($response, $response['status'] == 1 ? 200 : 500);
    }

    
}
