<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTypes extends Migration
{
    
    public function up()
    {
        Schema::create('wallet_types', function (Blueprint $table) {
            $table->id();
            $table->string('ar_name')->unique();
            $table->string('en_name')->unique();
            $table->string('type_name')->unique();
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('wallet_types');
    }
}
