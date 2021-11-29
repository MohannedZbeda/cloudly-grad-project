<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductValues extends Migration
{
    
    public function up()
    {
        Schema::create('product_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('RESTRICT');
            $table->foreignId('attribute_id')->references('id')->on('attributes')->onDelete('RESTRICT');
            $table->string('value');
            $table->timestamps();
        });
    }

  
    public function down()
    {
        Schema::dropIfExists('product_values');
    }
}
