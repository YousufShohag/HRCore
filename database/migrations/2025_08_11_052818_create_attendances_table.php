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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->timestamp('clock_in')->nullable();
            $table->string('clock_in_photo')->nullable();
            $table->timestamp('clock_out')->nullable();
            $table->string('clock_out_photo')->nullable();
            $table->decimal('total_hours', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
