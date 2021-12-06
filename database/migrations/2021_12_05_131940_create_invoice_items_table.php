<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
  
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->references('id')->on('invoices')->onDelete('RESTRICT');
            $table->bigInteger('invoiceable_id')->unsigned();
            $table->string('invoiceable_type');
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
}
