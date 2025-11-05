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
        Schema::create('consumers', function (Blueprint $table) {
            $table->id();
            $table->string('consumer_id')->unique()->nullable();
            $table->string('name');
            $table->string('cnic')->unique();
            $table->text('address');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->foreignId('subdivision_id')->constrained()->onDelete('cascade');
            $table->string('connection_type')->default('Domestic'); // Domestic, Commercial, Industrial
            $table->string('status')->default('active'); // active, disconnected, suspended
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumers');
    }
};

