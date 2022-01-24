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
            $table->string('ar_name')->nullable()->unique();
            $table->string('en_name')->nullable()->unique();
            $table->double('price')->min(0);
            $table->boolean('customized')->default(false);
            $table->string('customized_by')->nullable();
            $table->double('discount_percentage')->nullable();
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('variants');
    }
}
