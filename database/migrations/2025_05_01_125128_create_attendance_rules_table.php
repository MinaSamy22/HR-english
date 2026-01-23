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

            $table->id(); // bigint auto increment
            $table->unsignedBigInteger('company_id');
            $table->string('timezone')->nullable()->default('Africa/Cairo');
            $table->decimal('late_deduction_percentage', 5, 2)->nullable();
            $table->decimal('half_day_deduction_percentage', 5, 2)->nullable();

            $table->integer('late_threshold_minutes')->nullable();
            $table->integer('half_day_threshold_minutes')->nullable();
            $table->integer('absent_threshold_minutes')->nullable();

            $table->longText('official_holidays')->nullable();

            $table->integer('vacation_balance')->nullable();

            $table->string('company_policy_pdf')->nullable();

            $table->timestamps();
        });

    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::enableForeignKeyConstraints();
        Schema::dropIfExists('attendance_rules');
        Schema::disableForeignKeyConstraints();
    }
};
