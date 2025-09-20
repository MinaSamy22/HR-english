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
        // Create locations table
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('polygon')->nullable(); // polygon points
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            $table->timestamps();
        });

        // Create pivot table for employees & locations
        Schema::create('employee_location', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('location_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
        });

        // Add branch_id to multiple existing tables
        $tables = [
            'administrations','departments','users','attendances',
            'vacations','deductions','taxes','insurances',
            'resignations','late_removal_requests','histories',
            'jobs','managers','payrolls','tasks','times'
        ];

        foreach ($tables as $tbl) {
            if (!Schema::hasColumn($tbl, 'branch_id')) {
                Schema::table($tbl, function (Blueprint $blueprint) {
                    $blueprint->foreignId('branch_id')
                        ->nullable()
                        ->constrained('branches')
                        ->onDelete('cascade')
                        ->after('company_id');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop branch_id from tables
        $tables = [
            'administrations','departments','users','attendances',
            'vacations','deductions','taxes','insurances',
            'resignations','late_removal_requests','histories',
            'jobs','managers','payrolls','tasks','times'
        ];

        foreach ($tables as $tbl) {
            if (Schema::hasColumn($tbl, 'branch_id')) {
                Schema::table($tbl, function (Blueprint $blueprint) use ($tbl) {
                    $blueprint->dropForeign([$tbl.'_branch_id_foreign']);
                    $blueprint->dropColumn('branch_id');
                });
            }
        }

        // Drop new tables
        Schema::dropIfExists('employee_location');
        Schema::dropIfExists('locations');
    }
};
