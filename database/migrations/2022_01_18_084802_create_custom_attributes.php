<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomAttributes extends Migration
{
  
    public function up()
    {
        Schema::create('custom_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->references('id')->on('attributes');
            $table->foreignId('product_id')->references('id')->on('products');
            $table->double('custom_price')->nullable();
            $table->integer('unit_max')->nullable();
            $table->integer('unit_min')->nullable();
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('custom_attributes');
    }
}
