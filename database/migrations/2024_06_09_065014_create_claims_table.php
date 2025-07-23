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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->integer('claim_id')->default(0);
            $table->integer('customer')->default(0);
            $table->integer('insurance')->default(0);
            $table->date('date')->nullable();
            $table->string('status')->nullable();
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->integer('parent_id')->default(0);
            $table->foreignId('state_id')->constrained('states')->onDelete('set null');
            $table->foreignId('city_id')->constrained('cities')->onDelete('set null');
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
        Schema::dropIfExists('claims');
    }
};
