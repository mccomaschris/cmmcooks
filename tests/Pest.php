<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extend(Tests\TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

function createAdmin(): User
{
    config(['app.allowed_admin_emails' => 'admin@example.com']);

    return User::factory()->create(['email' => 'admin@example.com']);
}
