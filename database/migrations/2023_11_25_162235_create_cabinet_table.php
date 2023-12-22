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
        Schema::create('cabinet', function (Blueprint $table) {
            $table->increments('cabid');
            $table->integer('cabinetname');
            $table->integer('branchid');
            $table->string('branchname');
            $table->timestamps();
            $table->string('created_by');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabinet');
    }
};
