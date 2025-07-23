<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominee_details', function (Blueprint $table) {
            $table->id();
            $table->integer('insurance')->default(0);
            $table->string('name')->nullable();
            $table->string('relation')->nullable();
            $table->date('dob')->nullable();
            $table->float('percentage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nominee_details');
    }
};
