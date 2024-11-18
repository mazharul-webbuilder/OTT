<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCouponCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 16);
            $table->timestamp('start_date');
            $table->timestamp('expiry_date')->default(DB::raw('CURRENT_TIMESTAMP'));;
            $table->string('discount_type');
            $table->double('discount_value');
            $table->double('maximum_amount_for_percent')->default(0);
            $table->boolean('for_new_user_only')->default(false);
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
        Schema::dropIfExists('coupon_codes');
    }
}
