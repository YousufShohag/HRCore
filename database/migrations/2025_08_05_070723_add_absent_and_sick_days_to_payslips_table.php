<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            $table->integer('absent_days')->default(0)->after('average_hours');
            $table->integer('sick_leave_days')->default(0)->after('absent_days');
            $table->integer('total_worked_days')->nullable()->after('sick_leave_days');
        });
    }

    public function down(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            $table->dropColumn('absent_days');
            $table->dropColumn('sick_leave_days');
            $table->dropColumn('total_worked_days'); 
        });
    }
};