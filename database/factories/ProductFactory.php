<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Product;
use App\Models\User;

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
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph(1),
            'quantity' => $this->faker->numberBetween(1,10),
            'status' => $this->faker->randomElement(

                [
                    Product::UNAVAILABLE_PRODUCTS,
                    Product::AVAILABLE_PRODUCTS
                ]
                ),

            'image' => $this->faker->randomElement(

                ['1.jpg','2.jpg','3.jpg']
            ),

            'seller_id' => User::all()->random()->id,

          ];
    }
}
