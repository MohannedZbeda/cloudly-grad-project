<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDurationCycles extends Migration
{
    
    public function up()
    {

        Schema::create('duration_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('duration_id')->references('id')->on('subscribtion_durations');
            $table->foreignId('cycle_id')->references('id')->on('subscribtion_cycles');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('duration_cycles');
    }
}
