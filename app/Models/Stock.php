<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $fillable=[
        'item_name',
        'vendor_name',
        'item_quantity',
        'inward_quantity',
        'outward_quantity',
        'remaining_quantity',
        
       ];
   
}
