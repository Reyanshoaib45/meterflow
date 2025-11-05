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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('application_no')->unique();
            $table->string('customer_name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('cnic')->nullable();
            $table->string('meter_type')->nullable();
            $table->string('meter_number')->nullable();
            $table->integer('load_demand')->nullable();
            $table->foreignId('subdivision_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('connection_type')->nullable();
            $table->string('status')->default('pending');
            $table->decimal('fee_amount', 10, 2)->nullable();
            // Assignment fields
            $table->foreignId('assigned_ro_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_ls_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_sdc_id')->nullable()->constrained('users')->onDelete('set null');
            // Installation tracking fields
            $table->date('installation_date')->nullable();
            $table->decimal('gps_latitude', 10, 8)->nullable();
            $table->decimal('gps_longitude', 10, 8)->nullable();
            $table->text('installation_remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};

