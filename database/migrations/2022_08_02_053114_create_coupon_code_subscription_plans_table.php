<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponCodeSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_code_subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coupon_code_id');
            $table->unsignedBigInteger('subscription_plan_id');
            $table->boolean('is_active')->default(true);
            $table->integer('maximum_apply')->nullable();
            $table->integer('maximum_single_user_apply')->nullable();
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
        Schema::dropIfExists('coupon_code_subscription_plans');
    }
}
