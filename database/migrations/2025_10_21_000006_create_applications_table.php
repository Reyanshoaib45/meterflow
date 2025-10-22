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