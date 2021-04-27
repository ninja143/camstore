<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_carts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id')->comment('id of the products table');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->comment('id of the users table');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedMediumInteger('quantity');
            $table->enum('status', ['Active', 'Inactive'])->comment('Active is understood as Active in Cart And Inactive is as in Wishlist');
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
        Schema::dropIfExists('user_carts');
    }
}
