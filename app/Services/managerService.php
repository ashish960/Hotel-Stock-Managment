<?php

namespace App\Services;                        

use Illuminate\Support\Facades\Hash;           
use Illuminate\Support\Facades\DB;             


use App\Models\Order;     
use App\Models\Recipe;
use App\Models\Stock;
//for manager Service

class managerService{

 //add order process
 public function addOrder($recipe_id){
    try{
        $recipe=Recipe::where(['id'=>$recipe_id])->first();
    
        if($recipe['recipe_status'] == '0'){
            return
            [
                'message' => 'sorry,given order is not available right now ',
                'status' =>1, 
            ];
        }
        else{
        DB::beginTransaction();
        $recipe = Order::create([
            'recipe_id' => $recipe_id,
         
       ]);
         DB::commit();
         return
           [
               'message' => 'order created succefully ',
               'status' =>1, 
           ];
           
   }
}
   
   catch(\Exception $err){
    
       DB::rollback();
       $recipe = null;
           if($recipe==null){
               return [
                     'message'=> 'internal server error',
                     'status'=> '0',
                     'error'=>$err->getMessage()
                     
               ];
           }
   }
  
}


//manager add Outward quantity
public function addOutward($item_name,$outward_quantity){
    try{
        
        $stock=Stock::where(['item_name'=>$item_name])->first();

        $total_outward_quantity=$stock['outward_quantity'] + $outward_quantity;
        if($stock['inward_quantity'] < $outward_quantity){
            return
            [
                'message' => 'Invalid request : the given outward_quantity is more than total remaining stock',
                'status' =>1, 
            ];
        }
        if($total_outward_quantity > $stock['item_quantity'] ){
            return
            [
                'message' => 'sorry,the given total_outward_quantity is more than total stock',
                'status' =>1, 
            ];
        }
        else{
        DB::beginTransaction();
        $stock->outward_quantity = $stock['outward_quantity'] + $outward_quantity;
        $stock->save();
    
         DB::commit();
         return
           [
               'message' => 'outward_quanitity_added_succefully ',
               'status' =>1, 
           ];
           
   }
}
   
   catch(\Exception $err){
    
       DB::rollback();
       $recipe = null;
           if($recipe==null){
               return [
                     'message'=> 'internal server error',
                     'status'=> '0',
                     'error'=>$err->getMessage()
                     
               ];
           }
   }
  
}



}