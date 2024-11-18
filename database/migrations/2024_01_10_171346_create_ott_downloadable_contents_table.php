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
        Schema::create('ott_downloadable_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ott_content_id');
            $table->integer('expire_in_days')->comment('How many days the content will be available on user device');
            $table->json('available_marketplace_ids')->comment('which devices will able to download');
            $table->json('downloadable_qualities')->comment('Example 144, 240, 360, 480, 720, ..........');
            $table->foreign('ott_content_id')->references('id')->on('ott_contents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ott_downloadable_contents');
    }
};
