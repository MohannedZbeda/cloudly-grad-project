<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->references('id')->on('categories')->onDelete('CASCADE');
            $table->string('ar_name')->unique();
            $table->string('en_name')->unique();
            $table->double('price');
            //$table->boolean('refundable')->default(true);
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
