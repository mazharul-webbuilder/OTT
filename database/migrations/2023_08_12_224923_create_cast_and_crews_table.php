<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastAndCrewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cast_and_crews', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->mediumText('about')->nullable();
            $table->string('dob')->nullable();
            $table->string('image')->nullable();
            $table->string('nationality')->nullable();
            $table->mediumText('upcomming')->nullable();
            $table->mediumText('previous')->nullable();
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
        Schema::dropIfExists('cast_and_crews');
    }
}
