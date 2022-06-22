<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auction>
 */
class AuctionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(4, false),
            'image' => $this->faker->imageUrl(),
            'start_date' => now(),
            'end_date' => now()->addDays(7),
            'starting_price' => $this->faker->randomFloat(2, 0, 8),
        ];
    }
}
