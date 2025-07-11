<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Saving>
 */
class SavingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // database/factories/SavingFactory.php

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'date' => $this->faker->dateTimeBetween('-60 days', 'now'),
            'type' => $this->faker->randomElement(['manual_add', 'manual_subtract', 'budget_remainder', 'adjustment']),
            'amount' => $this->faker->numberBetween(50000, 500000),
            'note' => $this->faker->sentence(4),
        ];
    }
}
