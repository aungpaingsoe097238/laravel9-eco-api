<?php

namespace Database\Seeders;

use App\Models\OrderPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $state_id = [1,2,3,4,5,6,7,8,9,10];
        foreach ($state_id as $id) {
            OrderPrice::create([
                'price' => fake()->numberBetween(100,500),
                'state_id' => $id
            ]);
        }

    }
}
