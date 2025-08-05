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
        Schema::create('extra_time_requests', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('employee_id');
        $table->date('date');
        $table->decimal('hours', 5, 2); // e.g., 2.50 hours
        $table->string('reason')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->timestamps();

        $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_time_requests');
    }
};
