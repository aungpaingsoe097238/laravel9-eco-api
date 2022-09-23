<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Card::create([
            'user_id' => User::inRandomOrder()->first()->id,
            'quantity' => fake()->randomDigitNot(0)
        ])->products()->sync(1);

        Card::create([
            'user_id' => User::inRandomOrder()->first()->id,
            'quantity' => fake()->randomDigitNot(0)
        ])->products()->sync(2);
    }
}
