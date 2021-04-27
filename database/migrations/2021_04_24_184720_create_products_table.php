<?php

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

            $table->string('name', 128);
            $table->unsignedBigInteger('category_id')->comment('id of the product_categories table');
            $table->foreign('category_id')->references('id')->on('product_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('description', 512);
            $table->float('price', 8, 2)->comment('Price of the Product');
            $table->unsignedMediumInteger('make')->comment('product built in year');

            $table->timestamps();
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
