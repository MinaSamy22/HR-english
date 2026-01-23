<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration
{
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade'); //on delete cascade de 34an lma ams7 al employee da from employees a3rf ams7o o kmaaan etms7 kman mn hna
            $table->integer('basic_salary')->nullable();
            $table->integer('bounas')->nullable();
            $table->integer('deductions')->nullable();
            $table->integer('attendance_deduction')->nullable();
            $table->integer('taxes')->nullable();

            $table->decimal('insurance', 10, 2)->nullable();

            $table->integer('rest_vacancy')->nullable();
            $table->integer('days_absent')->nullable();
            $table->integer('daily_wage')->nullable();

            $table->string('payroll_type')->nullable();
            $table->integer('net_pay')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('payrolls');
        Schema::enableForeignKeyConstraints();
    }
}
