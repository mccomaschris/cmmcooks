import { readdir, readFile } from 'node:fs/promises';
import { parse } from 'yaml';

const readCollection = async (name) => Promise.all((await readdir(`src/content/${name}`))
  .filter((file) => file.endsWith('.yaml'))
  .map(async (file) => ({ id: file.slice(0, -5), ...parse(await readFile(`src/content/${name}/${file}`, 'utf8')) })));
const recipes = await readCollection('recipes');
const categories = await readCollection('categories');
const ids = new Set(categories.map(({ id }) => id));
for (const recipe of recipes) {
  if (!recipe.name || !recipe.ingredients?.length || !recipe.instructions?.length) throw Error(`Incomplete recipe: ${recipe.id}`);
  for (const category of recipe.categories || []) {
    if (!ids.has(category)) throw Error(`Unknown category ${category} in ${recipe.id}`);
  }
}
const ingredientCount = recipes.reduce((sum, recipe) => sum + recipe.ingredients.length, 0);
const instructionCount = recipes.reduce((sum, recipe) => sum + recipe.instructions.length, 0);
console.log(`${recipes.length} recipes, ${ingredientCount} ingredients, ${instructionCount} instructions, ${categories.length} categories`);
