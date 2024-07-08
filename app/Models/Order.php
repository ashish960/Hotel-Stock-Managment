<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable=[
     'recipe_id',
     'order_price'
       ];
   
       public function recipe()
       {
           return $this->belongsTo(Recipe::class);
       }
     
    }