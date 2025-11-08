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
        Schema::table('global_summaries', function (Blueprint $table) {
            $table->string('noc_file')->nullable()->after('date_return_sdc_billing');
            $table->string('demand_notice_file')->nullable()->after('noc_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_summaries', function (Blueprint $table) {
            $table->dropColumn(['noc_file', 'demand_notice_file']);
        });
    }
};
