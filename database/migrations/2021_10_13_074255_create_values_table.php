<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->references('id')->on('attributes')->onDelete('RESTRICT');
            $table->string('value');
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('values');
    }

}
