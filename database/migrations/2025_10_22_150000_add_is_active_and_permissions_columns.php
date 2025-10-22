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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('role');
            $table->text('permissions')->nullable()->after('is_active');
        });

        Schema::table('subdivisions', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('ls_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'permissions']);
        });

        Schema::table('subdivisions', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
