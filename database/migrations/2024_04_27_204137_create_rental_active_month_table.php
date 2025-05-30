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
        Schema::create('rental_active_month', function (Blueprint $table) {
            $table->increments('ramid');
            $table->integer('rpmonth');
            $table->integer('rpyear');
            $table->string('rpnotes');
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by');
            $table->dateTime('timerecorded');
            $table->string('posted');
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
        Schema::dropIfExists('rental_active_month');
    }
};
