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
        Schema::create('companies', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->bigInteger('phone_number')->nullable();
            $table->string('commercial_registration')->nullable();
            $table->string('tax_card')->nullable();
            $table->string('google_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('companies');
        Schema::enableForeignKeyConstraints();
    }
};
