<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the actual foreign key name from database
        $foreignKeyName = DB::select("SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'global_summaries' 
            AND COLUMN_NAME = 'application_id' 
            AND REFERENCED_TABLE_NAME IS NOT NULL 
            LIMIT 1");
        
        if (!empty($foreignKeyName)) {
            $fkName = $foreignKeyName[0]->CONSTRAINT_NAME;
            
            Schema::table('global_summaries', function (Blueprint $table) use ($fkName) {
                $table->dropForeign([$fkName]);
            });
        }
        
        // Make application_id nullable first (required for set null)
        Schema::table('global_summaries', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable()->change();
        });
        
        // Recreate with set null instead of cascade
        Schema::table('global_summaries', function (Blueprint $table) {
            $table->foreign('application_id')
                  ->references('id')
                  ->on('applications')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_summaries', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['application_id']);
            
            // Recreate with cascade (original behavior)
            $table->foreign('application_id')
                  ->references('id')
                  ->on('applications')
                  ->onDelete('cascade');
        });
    }
};
