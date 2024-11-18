<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBlackListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_black_lists', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_date');
            $table->mediumText('reason')->nullable();
            $table->dateTime('finish_date')->nullable();
            $table->boolean('status')->default(false);
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('user_black_lists');
    }
}
