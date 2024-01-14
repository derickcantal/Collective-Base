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
            $table->string('cabinetname');
            $table->integer('cabinetprice');
            $table->integer('branchid');
            $table->string('branchname');
            $table->integer('userid');
            $table->string('email');
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by');
            $table->string('timerecorded');
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
        Schema::dropIfExists('cabinet');
    }
};
