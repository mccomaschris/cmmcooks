# CMM Cooks

A static Astro recipe site. Recipes and categories live as YAML files in `src/content`; Pages CMS edits those files through GitHub. Cloudflare Workers Static Assets serves the built `dist` directory.

## Local development

```sh
npm ci
npm run dev
```

`npm run check` validates Astro and TypeScript. `npm run build` generates the static site. `npm run verify:content` checks category references and required recipe lists.

The one-time import source is `migration/recipes_export.json`, a recipe-only export bundled with the former Laravel app. `npm run verify:migration` compares recipe names, filenames, category links, and the order and text of every ingredient and instruction against that export. It contains 17 recipes, 132 ingredients, 65 instructions, and five categories. The September 23 SQL archive described in the migration plan was not present in this workspace. Compare it with the deployed YAML content as soon as it is available, especially original slugs, descriptions, notes, and images. Keep SQL archives out of Git.

## Editing

Connect this GitHub repository to [Pages CMS](https://app.pagescms.org/). The root `.pages.yml` provides recipe and category editors. Recipe filenames are the public slugs and cannot be renamed through the editor. Editing a title leaves its URL intact. New images are saved in `public/media`. Ingredient and instruction order in the editor is the order on the recipe page.

## Cloudflare deployment

The `cmmcooks` Worker is deployed to `www.cmmcooks.com`, `cmmcooks.com`, and `cmmcooks.mccomas-chris.workers.dev`. The `wrangler.jsonc` file configures the static asset directory, custom domains, and a 404 page. No Astro server adapter or runtime Worker code is needed.

Remaining checks and automation:

1. Compare the missing September 23 SQL archive with the YAML data, especially slugs, descriptions, notes, images, and order. The bundled JSON export does not contain all database fields.
2. On a preview branch, make a Pages CMS recipe edit and confirm its Git commit triggers a successful Workers preview build.
3. Inspect `/`, `/recipes`, and existing `/recipes/{slug}` links in the preview. Test search, category filtering, empty results, and the screen wake lock in a supported secure browser.
4. Connect this repository to Workers Builds with build command `npm run build`, deploy command `npx wrangler deploy`, and preview command `npx wrangler preview`.

Pages CMS and Git-connected Workers Builds still require their repository connections.
