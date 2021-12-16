<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->references('id')->on('wallet_types')->onDelete('RESTRICT');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->unique(['type_id', 'user_id']);
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}