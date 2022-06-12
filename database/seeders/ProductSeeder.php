<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Product::factory()->count(1000)->create()->each(

            function($product){

                  $categories = Category::all()->random(mt_rand(1,5))->pluck('id');
                  $product->categories()->attach($categories);
            }
        );
    }
}
