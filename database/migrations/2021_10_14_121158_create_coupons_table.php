<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->double('amount')->min(0)->max(100);
            $table->boolean('used')->default(false);
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
