<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('performance_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('employee_id'); // user_id where is_role = 0
            $table->unsignedBigInteger('evaluator_id'); // user_id where is_role = 1 (HR)
            $table->string('evaluation_period'); // e.g., "Q1 2024", "January 2024"
            $table->year('evaluation_year');

            // Custom criteria support
            $table->json('criteria_scores')->nullable(); // Store custom criteria scores
            $table->boolean('uses_custom_criteria')->default(false);

            $table->decimal('overall_score', 3, 2)->default(0.00);

            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('goals_for_next_period')->nullable();
            $table->text('hr_comments')->nullable();

            // Status
            $table->enum('status', ['draft', 'completed', 'reviewed'])->default('draft');

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('evaluator_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('performance_evaluations');
    }
};
