<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('photo_galaries', function (Blueprint $table) {
            $table->id();
            $table->mediumText('title');
            $table->mediumText('image');
            $table->mediumText('short_title')->nullable();
            $table->longText('description')->nullable();
            $table->enum('type',['FAMILY_MEMBERS','GALARY'])->default('GALARY');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_galaries');
    }
};
