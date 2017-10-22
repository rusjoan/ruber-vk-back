<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductsTabl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('owner_id');
            $table->string('product_id');
            $table->string('model')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->integer('created_at')->nullable();
            $table->json('category')->nullable();
            $table->string('thumb_photo')->nullable();
            $table->json('price')->nullable();
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
