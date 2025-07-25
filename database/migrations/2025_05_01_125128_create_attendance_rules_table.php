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
        Schema::create('attendance_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');

            $table->json('working_days')->nullable(); // the week contain 7 days some companies work 5 and others 6 based on the days of work estimate the wage of day
            $table->integer('vacation_balance')->nullable();
            $table->decimal('late_deduction_percentage', 5, 2)->nullable(); // deduct % amount from daily wage based on policy
            $table->decimal('half_day_deduction_percentage', 5, 2)->nullable(); // deduct % amount from daily wage based on policy
            $table->json('official_holidays')->nullable(); // not deduct the attendance of the offical holdays ex. eastern holiday, labor day,..
            $table->decimal('work_hours_per_day', 4, 2)->nullable();
            $table->decimal('bonus_per_hour', 5, 2)->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_rules');
    }
};
