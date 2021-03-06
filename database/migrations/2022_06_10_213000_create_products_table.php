<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description',1000);
            $table->integer('quantity',false,true);
            $table->string('status')->default(Product::UNAVAILABLE_PRODUCTS);
            $table->string('image');
            $table->integer('seller_id',false,true);
            $table->enum('color',['red','blue','purple']);
            $table->timestamps();

            $table->foreign('seller_id')->references("id")->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
