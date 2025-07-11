<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // database/factories/TransactionFactory.php

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'budget_id' => \App\Models\Budget::factory(),
            'category_id' => null, // atau bisa isi \App\Models\Category::factory()
            'date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'description' => $this->faker->sentence(3),
            'amount' => $this->faker->numberBetween(5000, 200000),
        ];
    }
}
