<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up()
    {
        Schema::create('ott_content_cast_and_crew', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ott_content_id');
            $table->unsignedBigInteger('cast_and_crew_id');
            $table->string('role')->nullable(); // e.g., main_character, tech_support, title_music
            $table->timestamps();
            $table->foreign('ott_content_id')->references('id')->on('ott_contents')->onDelete('cascade');
            $table->foreign('cast_and_crew_id')->references('id')->on('cast_and_crews')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ott_content_cast_and_crew');
    }
};
