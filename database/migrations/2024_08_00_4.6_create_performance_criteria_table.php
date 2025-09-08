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
        Schema::create('performance_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Quality of Work", "Communication"
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['company_id', 'is_active']);
        });

        // Add criteria_scores JSON column to performance_evaluations table
        Schema::table('performance_evaluations', function (Blueprint $table) {
            $table->json('criteria_scores')->nullable()->after('initiative');
            $table->boolean('uses_custom_criteria')->default(false)->after('criteria_scores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('performance_evaluations', function (Blueprint $table) {
            $table->dropColumn(['criteria_scores', 'uses_custom_criteria']);
        });

        Schema::dropIfExists('performance_criteria');
    }
};
