<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
            $table->string('status')->nullable()->default('pending');
            $table->boolean('is_seen')->default(false);

            $table->timestamps();

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
