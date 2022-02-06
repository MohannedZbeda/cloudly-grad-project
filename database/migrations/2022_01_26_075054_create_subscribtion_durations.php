<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribtionDurations extends Migration
{
    
    public function up()
    {
        Schema::create('subscribtion_durations', function (Blueprint $table) {
            $table->id();
            $table->integer('months');
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('subscribtion_durations');
    }
}
