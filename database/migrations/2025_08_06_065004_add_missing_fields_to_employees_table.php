<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'dob')) {
                $table->date('dob')->nullable()->after('basic_salary');
            }
            if (!Schema::hasColumn('employees', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('dob');
            }
            if (!Schema::hasColumn('employees', 'bank_account_number')) {
                $table->string('bank_account_number')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('employees', 'image')) {
                $table->string('image')->nullable()->after('bank_account_number');
            }
            if (!Schema::hasColumn('employees', 'document')) {
                $table->string('document')->nullable()->after('image');
            }
            if (!Schema::hasColumn('employees', 'join_date')) {
                $table->date('join_date')->nullable()->after('document');
            }
            if (!Schema::hasColumn('employees', 'resign_date')) {
                $table->date('resign_date')->nullable()->after('join_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'dob',
                'bank_name',
                'bank_account_number',
                'image',
                'document',
                'join_date',
                'resign_date',
            ]);
        });
    }
};
