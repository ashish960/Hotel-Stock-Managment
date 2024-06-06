<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
  protected $fillable=[
     'recipe_name',
     'recipe_status',
  ];

  public function items()
  {
      return $this->belongsToMany(Item::class, 'recipeprocesses','recipe_id','item_id')->withPivot('item_quantity');
  }

}
