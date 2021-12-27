<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribeablesTable extends Migration
{
    
    public function up()
    {
        Schema::create('subscribeables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->references('id')->on('subscriptions')->onDelete('RESTRICT');
            $table->bigInteger('subscribeable_id')->unsigned();
            $table->string('subscribeable_type');
            $table->date('start_date')->nullable();
            $table->integer('duration')->nullable();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('subscribeables');
    }
}
