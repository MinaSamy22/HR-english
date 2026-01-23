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
        Schema::create('extra_time_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');

            $table->date('date');
            $table->decimal('hours', 5, 2); // e.g., 2.50 hours
            $table->string('reason')->nullable();
            $table->string('status')->nullable()->default('pending');
            $table->tinyInteger('is_seen')->default(false);

            $table->timestamps();
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
