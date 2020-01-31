<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('product_id');
            $table->string('product_name')->nullable();
            $table->string('product_status')->nullable();
            $table->string('product_material')->nullable();
            $table->string('product_category')->nullable();
            $table->string('product_target')->nullable();
            $table->string('product_continuity')->nullable();
            $table->string('product_quantity')->nullable();
            $table->string('product_price')->nullable();
            $table->string('product_period')->nullable();
            $table->string('product_package')->nullable();
            $table->string('product_location')->nullable();
            $table->string('product_state')->nullable();
            $table->string('product_transport')->nullable();
            $table->string('product_description')->nullable();
            $table->string('product_image')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
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
