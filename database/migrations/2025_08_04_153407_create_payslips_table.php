<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('payslips', function (Blueprint $table) {
    //         $table->id();
    //         $table->timestamps();
    //     });
    // }

//     public function up(): void
// {
//     Schema::create('payslips', function (Blueprint $table) {
//         $table->id();
//         $table->foreignId('employee_id')->constrained()->onDelete('cascade');
//         $table->date('month');
//         $table->decimal('allowance', 10, 2)->default(0);
//         $table->decimal('deduction', 10, 2)->default(0);
//         $table->decimal('net_salary', 10, 2);
//         $table->timestamps();
//     });
// }

public function up(): void
{
    Schema::create('payslips', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->date('month');
        $table->integer('total_hours');
        $table->decimal('average_hours', 5, 2);
        $table->integer('absent_days')->default(0);
        $table->integer('sick_leave_days')->default(0);
        $table->decimal('salary', 10, 2);
        $table->decimal('allowance', 10, 2)->default(0);
        $table->decimal('deduction', 10, 2)->default(0);
        $table->decimal('net_salary', 10, 2);
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
