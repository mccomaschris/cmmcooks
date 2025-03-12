<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Support\Str;

class RecipeSeeder extends Seeder
{
    public function run()
    {
        $recipes = json_decode(file_get_contents(database_path('seeders/recipes_export.json')), true);

        foreach ($recipes as $recipe) {
            $newRecipe = Recipe::create([
                'name' => $recipe['title'],
                'slug' => Str::slug($recipe['title']),
            ]);

            $position = 0;
            foreach($recipe['ingredients'] as $ingredient) {
                $newRecipe->ingredients()->create([
                    'position' => $position++,
                    'name' => $ingredient,
                ]);
            }

            $position = 0;
            foreach($recipe['instructions'] as $instruction) {
                $newRecipe->instructions()->create([
                    'position' => $position++,
                    'name' => $instruction,
                ]);
            }

            $categoryIds = [];
            foreach ($recipe['categories'] as $categoryName) {
                $category = Category::firstOrCreate(['name' => $categoryName, 'slug' => Str::slug($categoryName)]);
                $categoryIds[] = $category->id;
            }

            $newRecipe->categories()->sync($categoryIds);
        }
    }
}
