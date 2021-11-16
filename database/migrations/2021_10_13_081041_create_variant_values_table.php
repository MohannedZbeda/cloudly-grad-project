<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantValuesTable extends Migration
{
    
    public function up()
    {
        Schema::create('variant_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')->references('id')->on('variants')->onDelete('RESTRICT');
            $table->foreignId('value_id')->references('id')->on('values')->onDelete('RESTRICT');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('variant_values');
    }
}
