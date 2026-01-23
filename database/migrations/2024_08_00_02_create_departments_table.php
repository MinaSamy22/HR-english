<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('department_name')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable()->constrained()->onDelete('set null');
            $table->unsignedBigInteger('administration_id')->nullable()->constrained()->onDelete('set null');
            $table->unsignedBigInteger('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('branch_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('departments');
        Schema::enableForeignKeyConstraints();
    }
};
