<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelectedCategoryContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selected_category_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('root_category_id');
            $table->unsignedBigInteger('ott_content_id'); 
            $table->boolean('is_featured')->default(false); 
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
        Schema::dropIfExists('selected_category_contents');
    }
}
