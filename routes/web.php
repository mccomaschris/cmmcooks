<?php

use App\Http\Middleware\IsAdmin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::livewire('/', 'site.index')->name('site.index');

Route::livewire('/recipes/{recipe}', 'recipes.show')->name('recipes.show');

Route::get('/recipes', function () {
    return Redirect::route('site.index');
})->name('recipes.index');

Route::middleware([isAdmin::class])->group(function () {
    Route::get('/admin', function () {
        return Redirect::route('admin.recipes.index');
    })->name('admin.index');

    Route::livewire('/admin/recipes', 'admin.recipes.index')->name('admin.recipes.index');
    Route::livewire('/admin/recipes/create', 'admin.recipes.create')->name('admin.recipes.create');
    Route::livewire('/admin/recipes/{recipe}/edit', 'admin.recipes.edit')->name('admin.recipes.edit');

    Route::get('/admin/recipes/{recipe}', function ($recipe) {
        return Redirect::route('recipes.show', $recipe);
    })->name('admin.recipes.show');
});

Route::get('/login', function () {
    return redirect('/google/redirect');
})->name('login');

Route::get('/google/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    $user = User::where('email', $googleUser->getEmail())->first();

    if ($user) {
        Auth::login($user);

        return Redirect::route('admin.index');
    } else {
        return response('Unauthenticated.', 401);
    }
});
