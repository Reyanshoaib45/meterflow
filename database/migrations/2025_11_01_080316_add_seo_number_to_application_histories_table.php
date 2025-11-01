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
        Schema::table('application_histories', function (Blueprint $table) {
            // Add SEO number field for RO
            $table->string('seo_number')->nullable()->after('remarks');
            // Add sent_to_ro flag
            $table->boolean('sent_to_ro')->default(false)->after('seo_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_histories', function (Blueprint $table) {
            $table->dropColumn(['seo_number', 'sent_to_ro']);
        });
    }
};
