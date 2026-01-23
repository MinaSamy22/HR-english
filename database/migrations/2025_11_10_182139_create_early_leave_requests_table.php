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
    Schema::create('early_leave_requests', function (Blueprint $table) {
        $table->id();
        
        $table->unsignedBigInteger('employee_id');
        $table->date('request_date');
        $table->time('requested_leave_time');
        $table->string('reason');
        $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
        $table->tinyInteger('is_seen')->default(0);
        $table->boolean('urgent_request')->default(false);
        $table->unsignedBigInteger('created_by')->nullable(); // employee system user
        $table->unsignedBigInteger('updated_by')->nullable(); // HR approver
        $table->timestamps();

        $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('early_leave_requests');
    }
};
