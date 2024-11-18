<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontendCustomContentSectionSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_custom_content_section_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('frontend_custom_content_type_id');
            $table->string('image');
            $table->string('content_url')->nullable();
            $table->enum('status', ['Pending', 'Hold', 'Published'])->default('Pending');
            $table->integer('order')->default(0);
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
        Schema::dropIfExists('frontend_custom_content_section_sliders');
    }
}
