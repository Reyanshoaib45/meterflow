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
        // Add subdivision_message to subdivisions table
        Schema::table('subdivisions', function (Blueprint $table) {
            $table->text('subdivision_message')->nullable()->after('is_active');
        });

        // Add public complaint fields to complaints table
        Schema::table('complaints', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('subdivision_id')->constrained()->onDelete('cascade');
            $table->string('customer_name')->nullable()->after('consumer_id');
            $table->string('phone', 20)->nullable()->after('customer_name');
            $table->string('consumer_ref')->nullable()->after('phone');
            $table->string('complaint_type')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subdivisions', function (Blueprint $table) {
            $table->dropColumn('subdivision_message');
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'customer_name', 'phone', 'consumer_ref', 'complaint_type']);
        });
    }
};
