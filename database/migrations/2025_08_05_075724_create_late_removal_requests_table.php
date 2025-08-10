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
       Schema::create('late_removal_requests', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('attendance_id'); // Link to attendance record
    $table->unsignedBigInteger('employee_id'); // The one who sent the request
    $table->date('day');
    $table->string('reason')->nullable();
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->timestamps();

    $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
    $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('late_removal_requests');
    }
};
