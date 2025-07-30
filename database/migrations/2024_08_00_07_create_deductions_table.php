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
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade'); //on delete cascade de 34an lma ams7 al employee da from employees a3rf ams7o o kmaaan etms7 kman mn hna
            $table->string('deduction_type')->nullable();
            $table->integer('money_deduction')->nullable();
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');

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
