<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCycleablesTable extends Migration
{
    
    public function up()
    {
        Schema::create('cycleables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_cycle_id')->references('id')->on('subscribtion_cycles');
            $table->bigInteger('cycleable_id')->unsigned();
            $table->string('cycleable_type');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('cycleables');
    }
}
