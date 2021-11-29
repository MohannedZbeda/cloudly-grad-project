<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->double('discount_amount')->nullable()->min(0);
            $table->double('discount_percentage')->nullable()->max(100)->min(0);
            $table->date('end_date');
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
