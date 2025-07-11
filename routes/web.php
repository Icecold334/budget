<?php

use App\Livewire\BudgetCreate;
use App\Livewire\Dashboard;
use App\Livewire\BudgetIndex;
use App\Livewire\SavingIndex;
use App\Livewire\CategoryIndex;
use App\Livewire\Settings\Profile;
use App\Livewire\TransactionIndex;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/budget', BudgetIndex::class)->name('budget.index');
    Route::get('/budget/create', BudgetCreate::class)->name('budget.create');
    Route::get('/transaction', TransactionIndex::class)->name('transaction.index');
    Route::get('/saving', SavingIndex::class)->name('saving.index');
    Route::get('/category', CategoryIndex::class)->name('category.index');
    // Route::get('/profile', ProfileIndex::class)->name('profile');
    // Route::get('/reports', ReportIndex::class)->name('report.index');


    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__ . '/auth.php';
