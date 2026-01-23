<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('vacation_requests', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('vacation_type')->nullable();
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();

        $table->string('reason')->nullable();
        $table->string('emergency_contact')->nullable();
        $table->tinyInteger('is_urgent')->default(false);
        $table->string('status')->default('pending'); // pending, approved, rejected
        $table->tinyInteger('is_seen')->default(false);
        $table->integer('total_days')->nullable();

        $table->timestamps();

    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacation_requests');
    }
};
