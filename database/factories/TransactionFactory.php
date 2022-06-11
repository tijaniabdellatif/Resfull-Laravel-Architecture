<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Seller;
use App\Models\User;

class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $seller = Seller::has('products')->get()->random();
        $buyer = User::all()->except($seller);
        return [

            'quantity' => $this->faker->numberBetween(1,3),
            'buyer_id' => $buyer->id ,
            'product_id' => $seller->products->random()->id,
        ];
    }
}
