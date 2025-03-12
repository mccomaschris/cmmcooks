<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['name', 'amount', 'note', 'recipe_id', 'position'];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
