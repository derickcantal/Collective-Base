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
        Schema::create('history_attendance', function (Blueprint $table) {
            $table->increments('attid');
            $table->integer('userid');
            $table->string('username');
            $table->string('firstname');
            $table->string('lastname');
            $table->integer('branchid');
            $table->string('branchname');
            $table->string('attnotes');
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
        Schema::dropIfExists('history_attendance');
    }
};
