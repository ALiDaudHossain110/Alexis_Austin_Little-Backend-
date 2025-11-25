<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_spendings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_source_id')->constrained('lead_sources')->onDelete('cascade');
            $table->year('year');
            $table->decimal('january', 10, 2)->nullable();
            $table->decimal('february', 10, 2)->nullable();
            $table->decimal('march', 10, 2)->nullable();
            $table->decimal('april', 10, 2)->nullable();
            $table->decimal('may', 10, 2)->nullable();
            $table->decimal('june', 10, 2)->nullable();
            $table->decimal('july', 10, 2)->nullable();
            $table->decimal('august', 10, 2)->nullable();
            $table->decimal('september', 10, 2)->nullable();
            $table->decimal('october', 10, 2)->nullable();
            $table->decimal('november', 10, 2)->nullable();
            $table->decimal('december', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_spendings');
    }
};
