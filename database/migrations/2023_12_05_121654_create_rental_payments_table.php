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
        Schema::create('rental_payments', function (Blueprint $table) {
            $table->increments('rpid');
            $table->integer('userid');
            $table->string('username');
            $table->string('firstname');
            $table->string('lastname');
            $table->decimal('rpamount', $precision = 8, $scale = 2);
            $table->decimal('rpbal', $precision = 8, $scale = 2);
            $table->decimal('rptotaldue', $precision = 8, $scale = 2);
            $table->string('rppaytype');
            $table->integer('rpmonth');
            $table->integer('rpyear');
            $table->string('rpnotes');
            $table->integer('branchid');
            $table->string('branchname');
            $table->integer('cabid');
            $table->string('cabinetname');
            $table->string('avatarproof');
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by');
            $table->dateTime('timerecorded');
            $table->string('posted');
            $table->string('fully_paid');
            $table->integer('mod');
            $table->string('copied')->nullable();
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_payments');
    }
};
