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
        Schema::create('extra_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('subdivision_id')->constrained()->onDelete('cascade');
            $table->string('application_no');
            $table->string('customer_name');
            $table->string('meter_no')->nullable();
            $table->date('sim_date')->nullable();
            $table->date('date_on_draft_store')->nullable();
            $table->date('date_received_lm_consumer')->nullable();
            $table->string('customer_mobile_no', 20)->nullable();
            $table->string('customer_sc_no')->nullable();
            $table->date('date_return_sdc_billing')->nullable();
            $table->foreignId('application_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_summaries');
    }
};