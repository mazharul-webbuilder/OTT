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
        Schema::table('admins', function (Blueprint $table) {
            $table->string('verification_code')->nullable()->after('remember_token');
            $table->timestamp('verification_code_created_at')->nullable()->after('verification_code');
            $table->tinyInteger('verify_attempt_left')->nullable()->default(0)->after('verification_code_created_at')     ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (DB::table('admins')->exists()){
                $table->dropColumn(['verification_code', 'verification_code_created_at', 'verify_attempt_left']);
            }
        });
    }
};
