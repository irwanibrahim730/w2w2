<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_fname')->nullable();
            $table->string('user_lname')->nullable();
            $table->string('companyname')->nullable();
            $table->string('password')->nullable();
            $table->string('user_contact')->nullable();
            $table->string('user_email')->nullable();
            $table->string('companyregisternumber')->nullable();
            $table->string('companydesc')->nullable(); 
            $table->string('address')->nullable();
            $table->string('occupation')->nullable();
            $table->string('job_title')->nullable();
            $table->string('user_type')->nullable();
            $table->string('profilepicture')->nullable();
            $table->string('user_role')->nullable();
            $table->string('package_id')->nullable();
            $table->string('product_id')->nullable();
            $table->string('package_limit')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
