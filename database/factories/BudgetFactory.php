<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Budget>
 */
class BudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::today()->subDays(rand(0, 30));
        $end = (clone $start)->addDays(30);

        return [
            'user_id' => User::factory(),
            'start_date' => $start,
            'end_date' => $end,
            'income_amount' => $this->faker->numberBetween(2000000, 10000000),
            'target_savings' => $this->faker->numberBetween(100000, 1000000),
            'status' => $this->faker->randomElement(['active', 'completed', 'cancelled']),
        ];
    }
}
