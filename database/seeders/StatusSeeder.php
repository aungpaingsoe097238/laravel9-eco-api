<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = ['Delivery','Waiting Payment','Just Now','Cancel'];

        foreach ($array as $key => $value)
        {
            Status::create([
                'name' => $value
            ]);
        }

    }
}
