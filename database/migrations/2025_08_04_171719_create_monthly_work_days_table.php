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
    //     Schema::create('monthly_work_days', function (Blueprint $table) {
    //         $table->id();
    //         $table->timestamps();
    //     });
    // }
public function up()
{
    Schema::create('monthly_work_days', function (Blueprint $table) {
        $table->id();
        $table->date('month')->unique();
        $table->integer('working_days');
        $table->integer('govt_holidays')->default(0);
        $table->integer('fridays')->default(0);
        $table->integer('special_holidays')->default(0);
        $table->integer('total_working_days');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_work_days');
    }
};
