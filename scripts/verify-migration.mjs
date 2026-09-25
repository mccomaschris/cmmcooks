import { readdir, readFile } from 'node:fs/promises';
import { parse } from 'yaml';

const source = JSON.parse(await readFile('migration/recipes_export.json', 'utf8'));
const slug = (value) => value.normalize('NFKD').replace(/[\u0300-\u036f]/g, '').toLowerCase()
  .replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
const files = (await readdir('src/content/recipes')).filter((file) => file.endsWith('.yaml'));
const categories = (await readdir('src/content/categories')).filter((file) => file.endsWith('.yaml'));
if (files.length !== source.length) throw Error('Recipe count differs from bundled export');
const expectedCategories = new Set(source.flatMap((recipe) => recipe.categories));
if (categories.length !== expectedCategories.size) throw Error('Category count differs from bundled export');
for (const name of expectedCategories) {
  const category = parse(await readFile(`src/content/categories/${slug(name)}.yaml`, 'utf8'));
  if (category.name !== name) throw Error(`Category mismatch: ${name}`);
}
for (const original of source) {
  const id = slug(original.title);
  const recipe = parse(await readFile(`src/content/recipes/${id}.yaml`, 'utf8'));
  if (recipe.name !== original.title ||
      JSON.stringify(recipe.categories) !== JSON.stringify(original.categories) ||
      JSON.stringify(recipe.ingredients.map((item) => item.name)) !== JSON.stringify(original.ingredients) ||
      JSON.stringify(recipe.instructions.map((item) => item.name)) !== JSON.stringify(original.instructions)) {
    throw Error(`Migration mismatch: ${id}`);
  }
}
console.log(`Migration matches bundled export: ${source.length} recipes, ${source.reduce((n, r) => n + r.ingredients.length, 0)} ingredients, ${source.reduce((n, r) => n + r.instructions.length, 0)} instructions, ${categories.length} categories.`);
