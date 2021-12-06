<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->foreignId('from_wallet_id')->references('id')->on('wallets')->onDelete('RESTRICT');
            $table->foreignId('to_wallet_id')->references('id')->on('wallets')->onDelete('RESTRICT');
            $table->double('amount');
            $table->double('balance_after_credit');
            $table->double('balance_after_debit');
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
