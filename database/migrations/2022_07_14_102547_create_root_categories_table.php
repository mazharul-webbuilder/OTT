<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRootCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('root_categories', function (Blueprint $table) {
            $table->id();
            $table->mediumText('title');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->string('seo_title')->nullable();
            $table->mediumText('seo_description')->nullable();
            $table->boolean('is_fixed')->default(0);
            $table->enum('status', ['Pending', 'Hold', 'Published'])->default('Pending');
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
        Schema::dropIfExists('root_categories');
    }
}
