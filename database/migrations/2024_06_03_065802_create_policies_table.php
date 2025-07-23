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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('liability_risk')->nullable();
            $table->string('coverage_type')->nullable();
            $table->integer('policy_type')->default(0);
            $table->integer('policy_subtype')->default(0);
            $table->integer('sum_assured')->default(0);
            $table->integer('total_insured_person')->default(1);
            $table->string('policy_required_document')->nullable();
            $table->string('claim_required_document')->nullable();
            $table->text('pricing')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('description')->nullable();
            $table->string('tax')->nullable();
            $table->string('parent_id')->default(0);
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
        Schema::dropIfExists('policies');
    }
};
