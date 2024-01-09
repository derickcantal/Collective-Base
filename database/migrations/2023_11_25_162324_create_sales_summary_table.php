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
        Schema::create('sales_summary', function (Blueprint $table) {
            $table->increments('sales_sid');
            $table->integer('branchid');
            $table->string('branchname');
            $table->integer('cabid');
            $table->string('cabinetname');
            $table->decimal('totalsales', $precision = 8, $scale = 2);
            $table->date('sales_date');
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('posted');
            $table->integer('mod');
            $table->string('status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_summary');
    }
};
