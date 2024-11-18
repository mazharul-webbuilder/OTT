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
            $table->unsignedBigInteger('creator_id')->nullable()->after('status')->comment('who create this content');
            $table->unsignedBigInteger('modifier_id')->nullable()->after('creator_id')->comment('who modified this content');
            $table->unsignedBigInteger('publisher_id')->nullable()->after('modifier_id')->comment('who published the content');

            $table->foreign('creator_id')->references('id')->on('admins');
            $table->foreign('modifier_id')->references('id')->on('admins');
            $table->foreign('publisher_id')->references('id')->on('admins');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ott_contents', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
            $table->dropForeign(['modifier_id']);
            $table->dropForeign(['publisher_id']);

            $table->dropColumn('creator_id');
            $table->dropColumn('modifier_id');
            $table->dropColumn('publisher_id');
        });
    }
};
