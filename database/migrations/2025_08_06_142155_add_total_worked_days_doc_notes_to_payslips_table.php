<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            $table->integer('total_worked_days')->nullable()->after('sick_leave_days');
            $table->string('document')->nullable()->after('total_worked_days');
            $table->longText('notes')->nullable()->after('document');
        });
    }

    public function down(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            $table->dropColumn('total_worked_days');
            $table->dropColumn('document');
            $table->dropColumn('notes');
        });
    }
};
