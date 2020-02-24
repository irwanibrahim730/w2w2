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
            $table->string('product_date')->nullable();
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
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->string('product_state')->nullable();
            $table->string('product_transport')->nullable();
            $table->string('product_description')->nullable();
            $table->string('product_image', 255)->nullable();
            $table->string('mainstatus')->nullable();
            $table->string('website')->nullable();
            $table->string('approved_at')->nullable();
            $table->string('expired_at')->nullable();
            $table->string('user_id')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_contact')->nullable();
            $table->unsignedInteger('package_id')->nullable();
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
