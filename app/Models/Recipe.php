<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['name', 'description', 'note', 'image'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class)->orderBy('position', 'asc');
    }

    public function instructions()
    {
        return $this->hasMany(Instruction::class)->orderBy('position', 'asc');
    }

    public function getStepsCountAttribute()
    {
        return $this->instructions->count();
    }

    public function getIngredientsCountAttribute()
    {
        return $this->ingredients->count();
    }

    public function similarRecipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_similar', 'recipe_id', 'similar_recipe_id');
    }

    public function similarRecipesBothWays()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_similar', 'recipe_id', 'similar_recipe_id')
                    ->orWhereHas('similarRecipes', function ($query) {
                        $query->where('similar_recipe_id', $this->id);
                    });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
