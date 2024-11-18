<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->string('plan_slug')->nullable();
            $table->string('discount_type')->nullable();
            $table->mediumText('description')->nullable();
            $table->boolean('is_discounted')->default(false);
            $table->double('discount_price')->nullable();
            $table->integer('discount_rate')->nullable();
            $table->integer('number_of_allowed_device')->default(0);
            $table->integer('number_of_allowed_stream')->default(0);
            $table->timestamp('discount_expiry_date')->nullable();
            $table->integer('duration');
            $table->double('regular_price');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->boolean('is_renewable')->default(false);
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
        Schema::dropIfExists('subscription_plans');
    }
}
