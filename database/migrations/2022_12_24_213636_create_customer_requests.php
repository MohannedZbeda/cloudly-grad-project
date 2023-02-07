<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerRequests extends Migration
{
    public function up()
    {
        Schema::create('customer_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->references('id')->on('users');
            $table->foreignId('sub_id')->references('id')->on('subscriptions');
            $table->boolean('status');
            $table->text('descreption');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_requests');
    }
}
