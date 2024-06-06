<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipeprocess extends Model
{
    use HasFactory;

    protected $fillable =[
        'recipe_id',
        'item_id',
        'item_quantity',
        'order_status',
        
    ];
}
