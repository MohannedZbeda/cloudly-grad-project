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
            $table->foreignId('cycle_id')->references('id')->on('subscribtion_cycles')->onDelete('RESTRICT');
            $table->string('name');
            $table->double('price');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
}
