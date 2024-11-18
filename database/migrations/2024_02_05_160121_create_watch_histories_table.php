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
        Schema::create('watch_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('customer or viewer');
            $table->unsignedBigInteger('ott_content_id');
            $table->string('ott_content_type');
            $table->timestamp('watched_at')->useCurrent();
            $table->integer('watched_duration')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('ott_content_id')->references('id')->on('ott_contents')->cascadeOnDelete();

            $table->unique(['user_id', 'ott_content_id', 'ott_content_type'], 'unique_watch_history');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watch_histories');
    }
};
