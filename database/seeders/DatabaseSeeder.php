<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Status;
use App\Models\Country;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Database\Seeders\CardSeeder;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\OrderSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\StockSeeder;
use Database\Seeders\StatusSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ProfileSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CitySeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(StateSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(StockSeeder::class);
        $this->call(ProfileSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(CardSeeder::class);

        // Create Foler For Images
        if(!Storage::exists('public/thumbnail')){
            Storage::makeDirectory('public/thumbnail');
        }

        if(!Storage::exists('public/profile')){
            Storage::makeDirectory('public/profile');
        }


        // Clean Storage
        $photos = Storage::allFiles('public');
        array_shift($photos);
        Storage::delete($photos);

        echo 'Storage Cleaned';

    }
}
