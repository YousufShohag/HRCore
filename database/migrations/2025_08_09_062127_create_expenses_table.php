<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Expense title (Electric bill, Rent, etc.)
        $table->text('description')->nullable();
        $table->decimal('amount', 10, 2);
        $table->date('expense_date');
        $table->string('category')->nullable(); // optional
        $table->timestamps();
    });
}
// public function up()
// {
//     Schema::create('expenses', function (Blueprint $table) {
//         $table->id();
//         $table->string('title'); // Expense name
//         $table->text('description')->nullable();
//         $table->decimal('amount', 10, 2); // Amount
//         $table->date('expense_date'); // Date of expense
//         $table->timestamps();
//     });
// }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
