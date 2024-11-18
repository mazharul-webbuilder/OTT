<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelProgramGuidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_program_guides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('schedule');
            $table->unsignedBigInteger('content_id');
            $table->foreign('content_id')->references('id')->on('ott_contents')->onDelete('cascade');
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
        Schema::dropIfExists('channel_program_guides');
    }
}
