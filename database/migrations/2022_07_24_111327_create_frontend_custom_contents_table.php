<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontendCustomContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_custom_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id');
            $table->timestamp('publish_date')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_upcoming')->default(false);
            $table->integer('sorting_position')->nullable();
            $table->unsignedBigInteger('frontend_custom_content_type_id')->index();
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
        Schema::dropIfExists('frontend_custom_contents');
    }
}
