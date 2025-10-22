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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no')->unique();
            $table->foreignId('consumer_id')->constrained()->onDelete('cascade');
            $table->foreignId('meter_id')->constrained()->onDelete('cascade');
            $table->foreignId('subdivision_id')->constrained()->onDelete('cascade');
            $table->string('billing_month');
            $table->integer('billing_year');
            $table->decimal('previous_reading', 10, 2)->nullable();
            $table->decimal('current_reading', 10, 2)->nullable();
            $table->decimal('units_consumed', 10, 2);
            $table->decimal('rate_per_unit', 8, 2);
            $table->decimal('energy_charges', 10, 2)->nullable();
            $table->decimal('fixed_charges', 8, 2)->default(0);
            $table->decimal('gst_amount', 10, 2)->default(0);
            $table->decimal('tv_fee', 8, 2)->default(0);
            $table->decimal('meter_rent', 8, 2)->default(0);
            $table->decimal('late_payment_surcharge', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('payment_status')->default('unpaid'); // paid, unpaid, overdue, partial
            $table->date('due_date');
            $table->date('issue_date');
            $table->date('payment_date')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
