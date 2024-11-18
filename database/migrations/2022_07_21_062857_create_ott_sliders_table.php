<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOttSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ott_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->mediumText('description')->nullable();
            $table->string('bottom_title')->nullable();
            $table->unsignedBigInteger('root_category_id');
            $table->string('slug')->nullable();
            $table->string('image');
            $table->string('content_url')->nullable();
            $table->enum('status', ['Pending', 'Hold', 'Published'])->default('Pending');
            $table->boolean('is_home')->default(false);
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
        Schema::dropIfExists('ott_sliders');
    }
}
