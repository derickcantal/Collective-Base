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
        Schema::create('customers_point', function (Blueprint $table) {
            $table->increments('cpid');
            $table->integer('cid');
            $table->string('username');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('email')->unique(); 
            $table->timestamps();
            $table->string('accesstype');
            $table->dateTime('tpointdatelast');
            $table->decimal('tpointslast', $precision = 8, $scale = 2);
            $table->decimal('tpoints', $precision = 8, $scale = 2);
            $table->dateTime('timerecorded');
            $table->string('created_by');
            $table->string('updated_by');
            $table->integer('mod');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers_point');
    }
};
