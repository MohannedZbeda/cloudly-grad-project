<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfoTable extends Migration
{
    
    public function up()
    {
        Schema::create('customer_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->string('phone')->unique();
            $table->string('password_reset_code')->unique()->nullable();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('user_info');
    }
}
