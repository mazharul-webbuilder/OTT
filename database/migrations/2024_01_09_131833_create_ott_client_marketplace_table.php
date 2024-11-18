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
        Schema::create('ott_content_marketplace', function (Blueprint $table) {
            $table->unsignedBigInteger('ott_content_id');
            $table->unsignedBigInteger('marketplace_id');

            $table->foreign('ott_content_id')->references('id')->on('ott_contents')->onDelete('cascade');
            $table->foreign('marketplace_id')->references('id')->on('marketplaces')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ott_client_marketplace');
    }
};
