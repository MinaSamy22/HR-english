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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('phone_number')->nullable();
            $table->date('hire_date')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('nationality', ['local', 'foreign'])->default('local');
            $table->string('country_code')->nullable();
            $table->date('residency_expiry')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->string('residency_number')->nullable();
            $table->string('iban')->nullable();
            $table->string('residency_job')->nullable();
            $table->unsignedBigInteger('job_id')->nullable()->index();
            $table->integer('salary_type')->nullable();
            $table->string('salary')->nullable();
            $table->time('work_start_time')->nullable();
            $table->time('work_end_time')->nullable();
            $table->tinyInteger('shift_count')->default(1);
            $table->time('second_work_start_time')->nullable();
            $table->time('second_work_end_time')->nullable();
            $table->string('macaddress')->nullable();
            $table->boolean('is_biometric')->nullable();
            $table->tinyInteger('main_salary')->nullable();
            $table->decimal('additional_salary', 10, 2)->nullable();
            $table->unsignedBigInteger('manager_id')->nullable()->index();
            $table->unsignedBigInteger('department_id')->nullable()->index();
            $table->string('attachment')->nullable();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->decimal('work_hours_per_day', 4, 2)->nullable();
            $table->json('working_days')->nullable();
            $table->decimal('vacation_balance', 8, 2)->nullable();
            $table->decimal('bonus_per_hour', 8, 2)->nullable();
            $table->rememberToken();
            $table->boolean('is_role')->default(1);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();  // Disable foreign key checks
        Schema::dropIfExists('users');  // Drop users table if it exists
        Schema::enableForeignKeyConstraints();  // Re-enable foreign key checks
    }
};
