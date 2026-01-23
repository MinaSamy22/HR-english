<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeductionsTable extends Migration
{
    public function up()
    {
        Schema::create('deductions', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('employee_id');

            $table->string('deduction_type')->nullable();
            $table->decimal('deduction_days', 5, 2)->nullable();
            $table->integer('money_deduction')->nullable();

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('deductions');
        Schema::enableForeignKeyConstraints();
    }
}
