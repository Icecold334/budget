<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Budget;
use App\Models\Saving;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory(4)
            ->has(Budget::factory()->count(2))
            ->has(Saving::factory()->count(3))
            ->has(Category::factory()->count(5))
            ->create();
    }
}
