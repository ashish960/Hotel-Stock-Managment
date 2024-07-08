<?php

namespace App\Services;                        

use Illuminate\Support\Facades\Hash;           
use Illuminate\Support\Facades\DB;             

use App\Models\Recipe;
use App\Models\Item;     
use App\Models\Stock;
use App\Models\Recipeprocess;
use App\Models\Order;
use App\Models\Stockreport;

use function PHPUnit\Framework\isEmpty;

//for admin Services

class adminService{

//add vendor and item

    public function addVendor($item,$vendor_name,$item_price){
        try{
            DB::beginTransaction();
            $vendor = Item::create([
                'item' => $item,
                'vendor_name' => $vendor_name,
                'item_price' => $item_price,
           ]);
             DB::commit();
             return
               [
                   'message' => 'Vendor created successfully',
                   'status' =>1,
                   
                   'vendor' => $vendor->makeHidden(['customer_password','created_at','updated_at']),
                   
                   
               ];
               
       }
       catch(\Exception $err){
        
           DB::rollback();
           $vendor = null;
               if($vendor==null){
                   return [
                         'message'=> 'internal server error',
                         'status'=> '0',
                         'error'=>$err->getMessage()
                         
                   ];
               }
       }
      
    }




//admin add stocks

public function addStock($item_name,$item_quantity,$outward_quantity){
    try{
        if($item_quantity < $outward_quantity){  //if invalid outward entry
            return
           [
               'message' => 'Invalid Entry:total  outward quantity should be less that  or equal to the total item quantity',
               'status' =>1,
           ];
        }
        else{
         $inward_quantity=$item_quantity-$outward_quantity;
         $vendor=Item::where(['item'=>$item_name])->first();
         $vendor_name=$vendor['vendor_name'];
        $previousData=Stock::where(['item_name'=>$item_name])->first();
        
        if($previousData == null){        //it stock previously exists
         
        DB::beginTransaction();
    
        $stock = Stock::create([
            'item_name' => $item_name,
            'vendor_name' => $vendor_name,
            'item_quantity' => $item_quantity,
            'inward_quantity'=>$inward_quantity,
            'outward_quantity'=>$outward_quantity
       ]);

       $stockreport = Stockreport::create([
        'item_name' => $item_name,
        'vendor_name' => $vendor_name,
        'item_quantity' => $item_quantity,
        'inward_quantity'=>$inward_quantity,
        'outward_quantity'=>$outward_quantity,
        'item_price'=>$item_quantity*$vendor['item_price'],
        'item_status'=>1,
   ]);
         DB::commit();

         return
           [
               'message' => 'stock added successfully',
               'status' =>1,
               'stock' => $stock->makeHidden(['created_at','updated_at']),
               
               
           ];
           
    }
    else{        //if not exists
        DB::beginTransaction();
    

      
            $previousData->item_quantity = $previousData['item_quantity'] + $item_quantity;
            $previousData->inward_quantity = $previousData['inward_quantity'] + $inward_quantity;
            $previousData->outward_quantity = $previousData['outward_quantity'] + $outward_quantity;
            $previousData->save();
        
            $stockreport = Stockreport::create([
                'item_name' => $previousData->item_name,
                'vendor_name' => $previousData->vendor_name,
                'item_quantity' => $item_quantity,
                'inward_quantity'=>$inward_quantity,
                'outward_quantity'=>$outward_quantity,
                'item_price'=>$item_quantity*$vendor['item_price'],
                'item_status'=>1,
           ]);
         DB::commit();

    
         return
           [
               'message' => 'stock added successfully',
               'status' =>1,
               'stock' => $previousData->makeHidden(['created_at','updated_at']),
               
               
           ];
           
   }
}
}
   catch(\Exception $err){
    
       DB::rollback();
       $stock = null;
       $previousData=null;
           if($stock==null || $previousData == null){
               return [
                     'message'=> 'internal server error',
                     'status'=> '0',
                     'error'=>$err->getMessage()
                     
               ];
           }
   }
  
 }





 //add recipe

public function addRecipe($recipe_name){
    try{
        DB::beginTransaction();
        $recipe = Recipe::create([
            'recipe_name' => $recipe_name,
           
       ]);
         DB::commit();
         return
           [
               'message' => 'recipe created successfully',
               'status' =>1,
               
               'vendor' => $recipe->makeHidden(['created_at','updated_at']),
               
               
           ];
           
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



 //add recipe process

 public function recipeProcess($recipe_id,$item_id,$item_quantity){
    try{
        $recipes=Recipe::where(['id'=>$recipe_id])->first();
        $item=Item::where(['id'=>$item_id])->first();
        DB::beginTransaction();
        $recipe = Recipeprocess::create([
            'recipe_id' => $recipe_id,
            'item_id'=>$item_id,
            'item_quantity'=>$item_quantity
       ]);
        
       $update_price=$item_quantity * $item['item_price'];
       
       $update_recipe_price=$recipes['recipe_price'] + $update_price;
       
       $recipes->recipe_price=$update_recipe_price;
       $recipes->save();

         DB::commit();
         return
           [
               'message' => '1 item added to receipe process ',
               'item'=>$item->makeHidden(['created_at','updated_at','vendor_name','item_price']),
               'item_quantity'=>$item_quantity,
               'status' =>1, 
           ];
           
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




//admin view recipe
public function viewRecipe($recipe_name){
    try{
        $recipe = Recipe::where(['recipe_name'=>$recipe_name])->with('items')->first();
        $recipe=$recipe->items->makeHidden(['created-at','updated_at','vendor_name','item_price']);
         return
           [
               'message' => 'recipe found successfully',
               'status' =>1,
               'recipe' => $recipe->makeHidden(['created_at','updated_at']),
           ];
           
   }
   catch(\Exception $err){
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



//admin view stock

public function viewStock(){
    $data=Stock::all();
    if($data == null){
      return  [
            'message' => 'no items in the stocks ',
            'status' =>1,

        ];
    
   
    } else{
        return  [
            'message' => 'item found ',
            'status' =>1,
            'data'=> $data->makeHidden(['updated_at','created_at'])

        ];
    }
}


//admin view Order

public function viewOrder(){
    $data=Order::with('recipe')->get();
    if($data == null){
      return  [
            'message' => 'no items in the order lists',
            'status' =>1,

        ];
    
   
    } else{
        return  [
            'message' => 'item found ',
            'status' =>1,
            'data'=> $data->makeHidden(['updated_at','created_at','order_price'])

        ];
    }
}



//admin view Report

public function viewReport($item_name,$date_from,$date_to){
    try{  if($item_name == null){
             if($date_from == $date_to){
                 $data=Stockreport::whereDate('created_at',$date_from)->get();
                 
             }
             else{
                 $data=Stockreport::whereBetween('created_at', [$date_from, $date_to])->get();
            }
        }
      else{
            $data=Stockreport::where(['item_name'=>$item_name])->whereBetween('created_at', [$date_from, $date_to])->get();
      }
      $data=$data->toArray();
      
      if(empty($data)){
        return [
            'message'=>'no record of selected date and item found',
            'status'=>1
        ];
      }
      else{
            return [
                   'message'=>'record found',
                   'data'=>$data,
                   'status'=>1
            ];
      }


}catch(\Throwable $err){
       return[
               'message'=>'internal server error',
               'status'=>0,
               'msg'=>$err->getmessage(),
       ];
}

}


}