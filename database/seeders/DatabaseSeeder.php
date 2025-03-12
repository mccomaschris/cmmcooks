<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RecipeSeeder;
use App\Models\User;
use Nette\Utils\Random;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $defaultEmails = explode(',', config('app.allowed_admin_emails'));

        foreach ($defaultEmails as $email) {
            User::create([
                'name' => 'Admin',
                'email' => $email,
                'password' => bcrypt(Random::generate(16)),
            ]);
        }

        $this->call([
		    RecipeSeeder::class,
		]);
    }
}
