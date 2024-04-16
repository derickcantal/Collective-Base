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
        Schema::create('branchlist', function (Blueprint $table) {
            $table->increments('BLID');
            $table->integer('userid');
            $table->integer('branchid');
            $table->string('accesstype');
            $table->string('timerecorded');
            $table->string('posted');
            $table->string('created_by');
            $table->string('updated_by');
            $table->integer('mod');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branchlist');
    }
};
