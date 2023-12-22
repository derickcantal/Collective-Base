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
        Schema::create('sales_requests', function (Blueprint $table) {
            $table->increments('salesrid');
            $table->integer('branchid');
            $table->string('branchname');
            $table->integer('cabid');
            $table->integer('cabinetname');
            $table->decimal('totalsales', $precision = 8, $scale = 2);
            $table->decimal('totalcollected', $precision = 8, $scale = 2);
            $table->string('avatarproof');
            $table->string('rnotes');
            $table->integer('userid');
            $table->string('firstname');
            $table->string('lastname');
            $table->timestamps();
            $table->string('updated_by');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_requests');
    }
};
