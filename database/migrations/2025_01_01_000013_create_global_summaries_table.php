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
        Schema::create('global_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('application_no');
            $table->string('customer_name');
            $table->text('consumer_address')->nullable();
            $table->string('meter_no')->nullable();
            $table->string('sim_number')->nullable();
            $table->date('date_on_draft_store')->nullable();
            $table->date('date_received_lm_consumer')->nullable();
            $table->string('customer_mobile_no')->nullable();
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
        Schema::dropIfExists('global_summaries');
    }
};

