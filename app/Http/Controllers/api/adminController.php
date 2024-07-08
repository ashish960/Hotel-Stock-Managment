<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//requests
use App\Http\Requests\addVendor; 
use App\Http\Requests\addStock; 
use App\Http\Requests\addRecipe; 
use App\Http\Requests\recipeProcess;
use App\Http\Requests\viewRecipe; 
use App\Http\Requests\viewReport; 

use App\Services\adminService; 
class adminController extends Controller
{
//admin services 
    protected $adminService;                                        
    public function __construct(adminService $adminService){        
        $this->adminService = $adminService;                         
    }    


//admin add vendor
    public function addVendor(addVendor $request){

   
        $response=$this->adminService->addVendor(
            $request->item,
            $request->vendor_name,
            $request->item_price,
           
        );
        return response()->json($response, $response['status'] == 1 ? 200 : 500);
    }



 //admin add stock
    public function addStock(addStock $request){

   
        $response=$this->adminService->addStock(
            $request->item_name,
            $request->item_quantity,
            $request->outward_quantity,
           
        );
        return response()->json($response, $response['status'] == 1 ? 200 : 500);
    }


//admin add recipe
       public function addRecipe(addRecipe $request){
        $response=$this->adminService->addRecipe(
            $request->recipe_name,
           
        );
        return response()->json($response, $response['status'] == 1 ? 200 : 500);
    }


//admin admincreaterecipe
     public function recipeProcess(recipeProcess $request){
        $response=$this->adminService->recipeProcess(
            $request->recipe_id,
            $request->item_id,
            $request->item_quantity
           
        );
        return response()->json($response, $response['status'] == 1 ? 200 : 500);
    }




 //admin admincreaterecipe
     public function viewRecipe(viewRecipe $request){
        $response=$this->adminService->viewRecipe(
            $request->recipe_name,
          
           
        );
        return response()->json($response, $response['status'] == 1 ? 200 : 500);
    }

 //admin view stocks
         public function viewStock(){
            $response=$this->adminService->viewStock();
            return response()->json($response, $response['status'] == 1 ? 200 : 500);
        }

        
  //admin view order
         public function viewOrder(){
            $response=$this->adminService->viewOrder();
            return response()->json($response, $response['status'] == 1 ? 200 : 500);
        }

 //admin view report
        public function viewReport(viewReport $request){
            $request['item_name'] = $request['item_name'] ?? null;
            $response=$this->adminService->viewReport(
                $request->item_name,
                $request->date_from,
                $request->date_to
       );
       return response()->json($response, $response['status'] == 1 ? 200 : 500);
}
}