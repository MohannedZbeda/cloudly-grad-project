<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->boolean('paid')->default(0);
            $table->double('total');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->softDeletes();
            $table->timestamps();
        });
    }

  
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
