<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resignations', function (Blueprint $table) {
            $table->id();
    $table->unsignedBigInteger('employee_id');
    $table->date('resignation_date');
    $table->string('reason')->nullable();
    $table->string('status')->default('pending'); // pending, approved, rejected
    $table->timestamps();

    $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');

});
    }

    public function down()
    {
        Schema::dropIfExists('employee_resignations');
    }
};
