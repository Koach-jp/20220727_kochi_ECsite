<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(100,10000),
            'stock' => $this->faker->numberBetween(0,20),
            'title' => $this->faker->realTextBetween(5,30,5),
            'description' => $this->faker->realTextBetween(100,200,5),
            'img_path' => 'storage/product-img/'.random_int(1,10).'.jpg',
        ];
    }
}
