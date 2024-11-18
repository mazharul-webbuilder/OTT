<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_plan_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->default(DB::raw('CURRENT_TIMESTAMP'));;
            $table->string('duration')->nullable();
            $table->boolean('is_auto_renewal')->default(false);
            $table->boolean('auto_reminder')->default(true);
            $table->mediumText('description')->nullable();
            $table->boolean('is_discounted')->default(false);
            $table->decimal('price', 15, 2);
            $table->boolean('is_active')->default(false);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')->onUpdate('cascade');
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
        Schema::dropIfExists('user_subscriptions');
    }
}
