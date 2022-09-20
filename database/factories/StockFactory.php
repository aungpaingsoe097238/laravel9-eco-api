<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'price' => fake()->numberBetween($min = 100, $max = 1000),
            'quantity' => fake()->numberBetween($min = 1, $max = 200) ,
            'product_id' => Product::inRandomOrder()->first()->id
        ];
    }
}
