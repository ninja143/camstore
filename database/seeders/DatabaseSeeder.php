<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\ProductCategory;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');
        // $this->call([
        //     UserSeeder::class,
        //     PostSeeder::class,
        //     CommentSeeder::class,
        // ]);

        // User Table Seeding
        User::factory()
            ->count(5)
            ->create();

        // Category Table Seeding
        ProductCategory::factory()
            ->count(5)
            ->create();

        // Product Table Seeding
        Product::factory()
            ->count(10)
            ->create();
    }
}
