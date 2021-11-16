<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageVariantsTable extends Migration
{
    
    public function up()
    {
        Schema::create('package_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->references('id')->on('packages')->onDelete('RESTRICT');
            $table->foreignId('variant_id')->references('id')->on('variants')->onDelete('RESTRICT');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('package_variants');
    }
}
