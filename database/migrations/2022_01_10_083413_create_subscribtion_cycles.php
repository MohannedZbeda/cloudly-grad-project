<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribtionCycles extends Migration
{

    public function up()
    {
    Schema::create('subscribtion_cycles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('months')->min(1);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('subscribtion_cycles');
    }
}
