<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('dob')->nullable()->after('basic_salary');
            $table->string('bank_name')->nullable()->after('dob');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('image')->nullable()->after('bank_account_number'); // path or filename
            $table->string('document')->nullable()->after('image'); // path or filename
            $table->date('join_date')->nullable()->after('document');
            $table->date('resign_date')->nullable()->after('join_date');
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
                'resign_date'
            ]);
        });
    }
};
