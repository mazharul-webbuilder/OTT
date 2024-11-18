<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *p
     * @return void
     */
    public function up()
    {
        Schema::create('content_sources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ott_content_id');
            $table->string('content_source')->nullable();
            $table->enum('processing_status',['missing','pending','processing','encoded'])->default('pending');
            $table->enum('source_type', ['trailer_raw_path', 'trailer_path', 'content_raw_path', 'content_path', 'audio_path', 'subtitle'])->default('content_raw_path');
            $table->timestamps();
            $table->string('key')->nullable();
            $table->string('size')->nullable();
            $table->string('video_type')->nullable();
            $table->index(['ott_content_id']);
            $table->foreign('ott_content_id')->references('id')->on('ott_contents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_sources');
    }
}
