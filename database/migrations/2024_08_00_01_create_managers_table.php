<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->date('hire_date')->nullable();
            $table->integer('salary')->nullable();
            $table->integer('commission_pct')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();

            // Adding the company_id foreign key
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('managers');
        Schema::enableForeignKeyConstraints();
    }
};
