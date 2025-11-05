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
        Schema::create('meters', function (Blueprint $table) {
            $table->id();
            $table->string('meter_no')->unique();
            $table->string('meter_make')->nullable();
            $table->decimal('reading', 10, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->string('meter_image')->nullable();
            $table->boolean('in_store')->default(true);
            $table->string('sim_number')->nullable();
            $table->string('seo_number')->nullable();
            $table->foreignId('application_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('consumer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('subdivision_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('status')->default('active'); // active, faulty, disconnected
            $table->date('installed_on')->nullable();
            $table->decimal('last_reading', 10, 2)->nullable();
            $table->date('last_reading_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meters');
    }
};

