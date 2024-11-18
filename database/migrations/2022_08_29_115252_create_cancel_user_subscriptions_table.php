<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancelUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancel_user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->mediumText('reason')->nullable();
            $table->unsignedBigInteger('user_subscription_plan_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('cancel_date');
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
        Schema::dropIfExists('cancel_user_subscriptions');
    }
}
