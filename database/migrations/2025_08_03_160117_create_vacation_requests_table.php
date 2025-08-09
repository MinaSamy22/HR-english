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
        $table->string('vacation_type');
        $table->date('start_date');
        $table->date('end_date')->nullable();
        $table->integer('total')->nullable();
        $table->text('reason');
        $table->string('emergency_contact')->nullable();
        $table->boolean('is_urgent')->default(false);
        $table->string('status')->default('pending'); // pending, approved, rejected
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
