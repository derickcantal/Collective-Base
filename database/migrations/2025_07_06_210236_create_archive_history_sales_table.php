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
        Schema::create('archive_history_sales', function (Blueprint $table) {
            $table->increments('salesid');
            $table->string('salesname');
            $table->string('salesavatar');
            $table->integer('cabid');
            $table->string('cabinetname');
            $table->string('productname');
            $table->integer('qty');
            $table->decimal('origprice', $precision = 8, $scale = 2);
            $table->decimal('srp', $precision = 8, $scale = 2);
            $table->decimal('total', $precision = 8, $scale = 2);
            $table->decimal('grandtotal', $precision = 8, $scale = 2);
            $table->string('payavatar');
            $table->string('paytype');
            $table->string('payref');
            $table->integer('userid');
            $table->string('username');
            $table->string('accesstype');
            $table->integer('branchid');
            $table->string('branchname');
            $table->integer('cid')->nullable();
            $table->timestamps();
            $table->string('collected_status');
            $table->string('returned');
            $table->string('snotes');
            $table->string('posted');
            $table->integer('mod');
            $table->string('created_by');
            $table->string('updated_by');
            $table->dateTime('timerecorded');
            $table->string('copied')->nullable();
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_history_sales');
    }
};
