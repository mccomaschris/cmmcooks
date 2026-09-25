import { readFile, mkdir, writeFile } from 'node:fs/promises';
import { stringify } from 'yaml';

const source = JSON.parse(await readFile('migration/recipes_export.json', 'utf8'));
const slug = (value) => value.normalize('NFKD').replace(/[\u0300-\u036f]/g, '').toLowerCase()
  .replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
const categories = new Set();

await mkdir('src/content/recipes', { recursive: true });
await mkdir('src/content/categories', { recursive: true });
for (const recipe of source) {
  const id = slug(recipe.title);
  recipe.categories.forEach((category) => categories.add(category));
  await writeFile(`src/content/recipes/${id}.yaml`, stringify({
    name: recipe.title,
    ...(recipe.description && { description: recipe.description }),
    categories: recipe.categories,
    ingredients: recipe.ingredients.map((name) => ({ name })),
    instructions: recipe.instructions.map((name) => ({ name })),
  }));
}
for (const name of categories) {
  await writeFile(`src/content/categories/${slug(name)}.yaml`, stringify({ name }));
}
console.log(`Imported ${source.length} recipes and ${categories.size} categories from Laravel's bundled seed export.`);
