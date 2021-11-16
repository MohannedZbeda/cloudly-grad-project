<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsTable extends Migration
{
    
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('CASCADE');
            $table->string('name');
            $table->double('price');
            $table->boolean('refundable')->default(true);
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('variants');
    }
}
