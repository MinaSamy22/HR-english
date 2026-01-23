<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->decimal('percent', 5, 2); // for make 12.50%

            $table->tinyInteger('apply_to_payroll')->default(false);

            $table->boolean('from_basic')->default(0);
            $table->boolean('from_housing')->default(0);
            $table->boolean('from_transportation')->default(0);
            $table->boolean('from_other_allowances')->default(0);

            $table->decimal('basic_percent', 5, 2)->default(0);
            $table->decimal('transportation_percent', 5, 2)->default(0);
            $table->decimal('housing_percent', 5, 2)->default(0);
            $table->decimal('other_allowances_percent', 5, 2)->default(0);

            $table->unsignedBigInteger('employee_id')->constrained('users')->onDelete('cascade'); //on delete cascade de 34an lma ams7 al employee da from employees a3rf ams7o o kmaaan etms7 kman mn hna
            $table->unsignedBigInteger('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->unsignedBigInteger('branch_id')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('taxes');
        Schema::enableForeignKeyConstraints();
    }
};
