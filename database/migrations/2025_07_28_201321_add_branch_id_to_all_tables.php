<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    protected $tables = [
        'managers', 'administrations', 'departments', 'jobs', 
        'users', 'histories', 'attendances', 'deductions', 'vacations', 
        'times', 'tasks', 'payrolls', 'insurances', 'attendance_rules'
    ];
    public function up(): void
    {

        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
            if (!Schema::hasColumn($table, 'branch_id')) {
                $blueprint->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                if (Schema::hasColumn($table, 'branch_id')) {
                    // This will automatically determine and drop the correct foreign key
                    $blueprint->dropForeign(['branch_id']);
                    $blueprint->dropColumn('branch_id');
                }
            });
        }
    }
};
