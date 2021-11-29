<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageProducts extends Migration
{
    
    public function up()
    {
        Schema::create('package_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->references('id')->on('packages')->onDelete('RESTRICT');
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('RESTRICT');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('package_products');
    }
}
