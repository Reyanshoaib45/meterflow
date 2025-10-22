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
        Schema::table('meters', function (Blueprint $table) {
            $table->foreignId('consumer_id')->nullable()->after('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('subdivision_id')->nullable()->after('consumer_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('active')->after('sim_number'); // active, faulty, disconnected
            $table->date('installed_on')->nullable()->after('status');
            $table->decimal('last_reading', 10, 2)->nullable()->after('installed_on');
            $table->date('last_reading_date')->nullable()->after('last_reading');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meters', function (Blueprint $table) {
            $table->dropForeign(['consumer_id']);
            $table->dropForeign(['subdivision_id']);
            $table->dropColumn(['consumer_id', 'subdivision_id', 'status', 'installed_on', 'last_reading', 'last_reading_date']);
        });
    }
};
