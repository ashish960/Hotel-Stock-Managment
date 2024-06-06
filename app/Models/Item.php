<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
      
    protected $fillable=[
        'item',
        'vendor_name',
        'item_price',
        
       ];
   

       public function recipe()
       {
           return $this->belongsToMany(Recipe::class, 'recipeprocesses','item_id','recipe_id')->withPivot('item_quantity');
       }
     


}
