<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $product_name_1 = $this->faker->randomElement(['Nikkon ', 'Cannon ', 'Sony ', 'Blue Star ', 'FlyCam ']);
        $product_name_2 = rand(111, 9999);
    	return [
    	    'name' => $this->faker->unique()->name('32'),
            'category_id' => $this->faker->randomElement(['1', '2', '3', '4', '5']),
            'description' => Str::random(128),
            'price' => $this->faker->randomNumber(2),
            'make' => rand(2012, 2021),
    	];
    }
}
