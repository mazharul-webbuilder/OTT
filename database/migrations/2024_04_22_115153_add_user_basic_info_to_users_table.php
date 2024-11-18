<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string(column: 'name')->nullable()->after('id');
            $table->string(column: 'email')->nullable()->unique()->after('name');
            $table->enum(column: 'gender', allowed: ['male', 'female', 'other'])->nullable()->after('phone');
            $table->date(column: 'dob')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (DB::table('users')->exists()){
                $table->dropColumn(columns: ['name', 'email', 'gender', 'dob']);
            }
        });
    }
};
