<?php

namespace App\Services;                        

use Illuminate\Support\Facades\Hash;           
use Illuminate\Support\Facades\DB;             


use App\Models\Order;     
use App\Models\Recipe;
use App\Models\Stock;
use App\Models\Item;
use App\Models\Stockreport;
//for manager Service

class managerService{

 //add order process
 public function addOrder($recipe_id){
    try{
        DB::beginTransaction();
        $recipe=Recipe::where(['id'=>$recipe_id])->first();
        $recipe_process = Recipe::where(['id'=>$recipe_id])->with('items')->first();
        
            $recipe_process = $recipe_process->items->toArray();
      
            foreach($recipe_process as $item){
                $item_name=$item['item'];
                $stock_item=Stock::where(['item_name'=>$item_name])->first();
                $vendor=Item::where(['item'=>$item_name])->first();
                if($stock_item == null){
                    return[
                        'message'=>'item doesnt exists in the stock',
                        'status'=>'1'
                    ];
                  }
               
                 if($stock_item['inward_quantity'] < $item['pivot']['item_quantity']){
                    return
                    [
                        'message' => 'sorry,given order is not available right now due to unavailablity of item ',
                        'status' =>1, 
                    ];
                }
                $stock_item->inward_quantity-= $item['pivot']['item_quantity'];
                 $stock_item->save();

                 $stockreport = Stockreport::create([
                    'item_name' => $stock_item->item_name,
                    'vendor_name' => $stock_item->vendor_name,
                    'inward_quantity' => $item['pivot']['item_quantity'],
                    'item_price'=> $item['pivot']['item_quantity']*$vendor['item_price'],
                    'item_status'=>2,
               ]);
                
                }
        $recipe = Order::create([
            'recipe_id' => $recipe_id,
                 ]);
        

                 
         DB::commit();
        
         return
           [
               'message' => 'order created succefully ',
               'status' =>1, 
               'recipe'=>$recipe
           ];
           
   }
   catch(\Exception $err){
       DB::rollback();
      
               return [
                     'message'=> 'internal server error',
                     'status'=> '0',
                     'error'=>$err->getMessage()
               ];
   }
  
}




//manager add Outward quantity
public function addOutward($item_name,$outward_quantity){
    try{
        
        $stock=Stock::where(['item_name'=>$item_name])->first();
        $item=Item::where(['item'=>$item_name])->first();

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

        
        $stockreport = Stockreport::create([
            'item_name' => $stock->item_name,
            'vendor_name' => $stock->vendor_name,
            'outward_quantity' =>  $outward_quantity,
            'item_price'=> $outward_quantity*$item['item_price'],
            'item_status'=>2,
       ]);
    
         DB::commit();
         return
           [
               'message' => 'outward_quanitity_added_succefully ',
               'status' =>1, 
               'stock'=>$stock
           ];
   }
}
   
   catch(\Throwable $err){
    
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