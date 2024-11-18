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
        Schema::create('t_vod_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_title');
            $table->double('price', 8, 2);
            $table->integer('access_limit')->default(1);
            $table->text('description')->nullable();
            $table->integer('ticket_duration_hour')->default(0);
            $table->integer('device_limit')->nullable();
            $table->boolean('is_ticket_duration_in_hour')->default(false);
            $table->unsignedBigInteger('ott_content_id')->unique();
            $table->foreign('ott_content_id')->references('id')->on('ott_contents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_vod_subscriptions');
    }
};
