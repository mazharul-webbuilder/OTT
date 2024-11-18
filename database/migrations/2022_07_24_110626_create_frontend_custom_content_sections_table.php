<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontendCustomContentSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_custom_content_sections', function (Blueprint $table) {
            $table->id();
            $table->integer('content_type_slug')->unique();
            $table->string('content_type_title');
            $table->string('more_info_slug')->nullable();
            $table->boolean('is_available_on_single_page')->default(false);
            $table->boolean('is_featured_section')->default(false);
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
        Schema::dropIfExists('frontend_custom_content_sections');
    }
}
