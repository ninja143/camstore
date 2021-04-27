<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition(): array
    {
    	return [
            'name' => $this->faker->unique()->randomElement(['Nikkon', 'Cannon', 'Sony', 'Blue Star', 'FlyCam']),
            'type' => $this->faker->randomElement(['Mirrorless', 'Full frame', 'Point And Shoot']),
            'model' => rand(2012, 2021),
        ];
    }
}
