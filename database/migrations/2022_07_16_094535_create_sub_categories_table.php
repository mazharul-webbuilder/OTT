<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->mediumText('title');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->string('seo_title')->nullable();
            $table->mediumText('seo_description')->nullable();
            $table->enum('status', ['Pending', 'Hold', 'Published'])->default('Pending');
            $table->unsignedBigInteger('root_category_id');
            $table->foreign('root_category_id')->references('id')->on('root_categories')->onDelete('cascade');
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
        Schema::dropIfExists('sub_categories');
    }
}
