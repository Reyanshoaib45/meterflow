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
        Schema::table('applications', function (Blueprint $table) {
            // Add assignment fields
            $table->foreignId('assigned_ro_id')->nullable()->after('status')->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_ls_id')->nullable()->after('assigned_ro_id')->constrained('users')->onDelete('set null');
            
            // Add installation tracking fields
            $table->date('installation_date')->nullable()->after('assigned_ls_id');
            $table->decimal('gps_latitude', 10, 8)->nullable()->after('installation_date');
            $table->decimal('gps_longitude', 10, 8)->nullable()->after('gps_latitude');
            $table->text('installation_remarks')->nullable()->after('gps_longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['assigned_ro_id']);
            $table->dropForeign(['assigned_ls_id']);
            $table->dropColumn([
                'assigned_ro_id',
                'assigned_ls_id',
                'installation_date',
                'gps_latitude',
                'gps_longitude',
                'installation_remarks'
            ]);
        });
    }
};

