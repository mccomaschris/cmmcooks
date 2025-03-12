<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }

    public function getRouteKeyName()
    {
        return "slug";
    }
}
