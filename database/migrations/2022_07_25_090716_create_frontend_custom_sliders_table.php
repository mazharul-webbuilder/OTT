<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontendCustomSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_custom_sliders', function (Blueprint $table) {
            $table->id();
            $table->integer('slider_type_slug');
            $table->string('slider_type_title');
            $table->string('slider_type_sub_title')->nullable();
            $table->string('press_action_slug')->nullable();
            $table->enum('press_action_slug_activity', ['single_content', 'category', 'sub_category', 'sub_sub_category'])->nullable();
            $table->boolean('is_active')->default(false);
            $table->integer('sorting_order')->default(0);
            $table->string('image');
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
        Schema::dropIfExists('frontend_custom_sliders');
    }
}
