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
        Schema::create('sales_eod', function (Blueprint $table) {
            $table->increments('seodid');
            $table->integer('branchid');
            $table->string('branchname');
            $table->decimal('totalsales', $precision = 8, $scale = 2);
            $table->decimal('rentalpayments', $precision = 8, $scale = 2);
            $table->decimal('requestpayments', $precision = 8, $scale = 2);
            $table->decimal('otherexpenses', $precision = 8, $scale = 2);
            $table->decimal('totalcash', $precision = 8, $scale = 2);
            $table->string('notes');
            $table->string('created_by');
            $table->string('updated_by');
            $table->dateTime('timerecorded');
            $table->string('posted');
            $table->string('copied')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_eod');
    }
};
