<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('cycle_id')->references('id')->on('subscribtion_cycles');
            $table->string('name');
            $table->text('record');
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
