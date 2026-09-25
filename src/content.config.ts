import { defineCollection } from 'astro:content';
import { glob } from 'astro/loaders';
import { z } from 'astro/zod';

const optionalText = z.string().nullable().optional();
const line = z.object({
  name: z.string().min(1),
  amount: optionalText,
  note: optionalText,
});

const recipes = defineCollection({
  loader: glob({ pattern: '**/*.yaml', base: './src/content/recipes' }),
  schema: z.object({
    name: z.string().min(1),
    description: optionalText,
    note: optionalText,
    image: optionalText,
    categories: z.array(z.string()).default([]),
    ingredients: z.array(line).min(1),
    instructions: z.array(line).min(1),
  }),
});

const categories = defineCollection({
  loader: glob({ pattern: '**/*.yaml', base: './src/content/categories' }),
  schema: z.object({ name: z.string().min(1) }),
});

export const collections = { recipes, categories };
