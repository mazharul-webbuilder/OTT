<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('currency');
            $table->string('charge_id')->unique();
            $table->string('payment_channel');
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('user_subscription_plan_id');
            $table->unsignedBigInteger('user_subscription_id');
            $table->string('status');
            $table->string('validation_token');
            $table->index('charge_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
