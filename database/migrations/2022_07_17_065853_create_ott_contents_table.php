<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOttContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ott_contents', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->mediumText('title');
            $table->mediumText('short_title')->nullable();
            $table->unsignedBigInteger('root_category_id');
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->unsignedBigInteger('sub_sub_category_id')->nullable();
            $table->unsignedBigInteger('series_id')->nullable();
            $table->unsignedBigInteger('season_id')->nullable();
            $table->unsignedBigInteger('episode_number')->nullable();
            $table->unsignedBigInteger('content_type_id')->nullable();
            $table->longText('description')->nullable();
            $table->longText('bangla_description')->nullable();
            $table->string('year')->nullable();
            $table->integer('runtime')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('cloud_url')->nullable();
            $table->string('intro_starts')->nullable();
            $table->string('intro_end')->nullable();
            $table->string('next_end')->nullable();
            
            $table->string('poster')->nullable();
            $table->string('backdrop')->nullable();
            $table->string('thumbnail_portrait')->nullable();
            $table->string('thumbnail_landscape')->nullable();
            $table->string('tv_cover')->nullable();
            $table->bigInteger('view_count')->nullable();
            $table->string('release_date')->nullable();

            $table->enum('status', ['Pending', 'Hold', 'Published'])->default('Pending');
            $table->enum('access', ['Premium', 'Free'])->default('Free');
            $table->integer('order')->default(0);
            $table->integer('series_order')->default(0);
            $table->integer('number_of_allowed_audience_per_user')->default(0);
            $table->foreign('root_category_id')->references('id')->on('root_categories')->onDelete('cascade');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
            $table->foreign('sub_sub_category_id')->references('id')->on('sub_sub_categories')->onDelete('cascade');
            $table->foreign('series_id')->references('id')->on('ott_series')->onDelete('cascade');

            $table->string('title_bangla')->nullable();
            $table->string('content_type')->nullable();
            // $table->enum('content_type', ['Single', 'Series'])->default('Single');
            $table->enum('vod_type', ['SVOD', 'AVOD', 'TVOD'])->default('AVOD');
            $table->enum('video_type', ['MP4', 'MOV', 'AVI'])->default('MP4');
            $table->string('upload_date')->nullable();
            $table->string('imdb')->nullable();
            $table->string('saga')->nullable();
            $table->string('is_original')->nullable();
            $table->mediumText('synopsis_english')->nullable();
            $table->mediumText('synopsis_bangla')->nullable();
            $table->mediumText('genre')->nullable();
            $table->mediumText('tags')->nullable();
            $table->mediumText('associated_teaser')->nullable();
            $table->mediumText('up_comming')->nullable();
            $table->unsignedBigInteger('content_owner_id')->nullable();
            $table->string('external_id')->unique()->nullable();
            $table->boolean('is_live_stream')->default(false);
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
        Schema::dropIfExists('ott_contents');
    }
}
