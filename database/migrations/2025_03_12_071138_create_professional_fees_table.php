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
        Schema::create('professional_fees', function (Blueprint $table) {
            $table->id();
            $table->integer('claim_id')->default(0);
            $table->decimal('professional_fee', 10, 2)->nullable();
            $table->decimal('reinspection_fee', 10, 2)->nullable();
            $table->date('date_of_visits')->nullable();
            $table->decimal('halting_charges', 10, 2)->nullable();
            $table->decimal('conveyance_final', 10, 2)->nullable();
            $table->integer('distance_final')->nullable();
            $table->decimal('rate_per_km_final', 10, 2)->nullable();
            $table->decimal('conveyance_reinspection', 10, 2)->nullable();
            $table->integer('distance_reinspection')->nullable();
            $table->decimal('rate_per_km_reinspection', 10, 2)->nullable();
            $table->integer('photos_count')->nullable();
            $table->decimal('photo_rate', 10, 2)->nullable();
            $table->decimal('toll_tax', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('cgst', 10, 2);
            $table->decimal('sgst', 10, 2);
            $table->decimal('igst', 10, 2)->nullable();
            $table->decimal('net_total', 10, 2);
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('branch_address')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('micr_code')->nullable();
            $table->string('id_no')->nullable();
            $table->string('gstin')->nullable();
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
        Schema::dropIfExists('professional_fees');
    }
};
