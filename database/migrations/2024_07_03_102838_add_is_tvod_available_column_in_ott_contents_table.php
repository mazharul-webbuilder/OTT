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
        Schema::table('ott_contents', function (Blueprint $table) {
            $table->boolean('is_tvod_available')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ott_contents', function (Blueprint $table) {
            $table->dropColumn(['is_tvod_available']);
        });
    }
};
