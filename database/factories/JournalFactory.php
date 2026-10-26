<?php

namespace Database\Factories;

use App\Models\Journal;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; 

/**
 * @extends Factory<Journal>
 */
class JournalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'content' => fake()->paragraph(3),
            'status' => fake()->randomElement(['submitted', 'reviewed', 'approved']),
            'user_id' => User::factory(),
        ];
    }
}
