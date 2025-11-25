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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            $table->string('phonenumber')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->nullable();

            $table->string('casetype')->nullable();
            $table->decimal('casevalue', 30, 2)->nullable();
            $table->string('location')->nullable();

            $table->boolean('qualified')->default(false);
            $table->string('unqualifiedcasetype')->nullable();
            $table->string('source')->nullable();

            $table->boolean('consultbooked')->default(false);
            $table->boolean('converted')->default(false);
            $table->timestamp('converted_date')->nullable();

            $table->boolean('consultdone')->default(false);
            $table->foreignId('user_id_consultantdoneby')
                ->nullable()
                ->constrained('users')
                ->onDelete('restrict');

            $table->string('leadstatus')->default('new');
            $table->Integer('number_of_follow_up_attempts')->default(0);
            $table->timestamp('last_date_of_contact')->nullable();
            $table->timestamp('consultation_book_date')->nullable();

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
