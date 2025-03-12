<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;
use App\Models\Recipe;
use Illuminate\Support\Str;

class RecipeForm extends Form
{
    public ?Recipe $recipe;

    public $name = '';
    public $description = '';
    public $note = '';
    public array $ingredients = [];
    public array $instructions = [];
    public array $categories = [];

    protected function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('recipes')->ignore($this->recipe),
            ],
            'description' => 'nullable',
            'note' => 'nullable',
            'ingredients' => 'array',
            'ingredients.*.ingredient' => 'required',
            'instructions' => 'array',
            'instructions.*.instruction' => 'required',
        ];
    }

    public function setRecipe(Recipe $recipe)
    {
        $this->recipe = $recipe;
        $this->name = $recipe->name;
        $this->description = $recipe->description;
        $this->note = $recipe->note;
        $this->ingredients = $recipe->ingredients->toArray() ?? [];
        $this->instructions = $recipe->instructions->toArray() ?? [];
    }

    public function store()
    {
        $this->validate();

        $recipe = Recipe::create([
            'name' => $this->name,
            'description' => $this->description,
            'note' => $this->note,
            'slug' => $this->generateUniqueSlug($this->name, $this->recipe->id),
        ]);

        $this->recipe->ingredients()->delete();
        foreach ($this->ingredients as $ingredient) {
            if (!empty($ingredient)) {
                Ingredient::create([
                    'recipe_id' => $this->recipe->id,
                    'ingredient' => $ingredient,
                ]);
            }
        }

        $this->recipe->instructions()->delete();
        foreach ($this->instructions as $instruction) {
            if (!empty($instruction)) {
                Instruction::create([
                    'recipe_id' => $this->recipe->id,
                    'instruction' => $instruction,
                ]);
            }
        }

        // $recipe->categories()->sync();

        $this->reset();
    }

    public function update()
    {
        $this->validate();

        $this->recipe->update(
            $this->all()
        );
    }

    private function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        // Check if the slug already exists in the database
        while (Recipe::where('slug', $slug)
            ->when($id, fn($query) => $query->where('id', '!=', $id))
            ->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

}
