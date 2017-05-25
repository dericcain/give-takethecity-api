<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecurringDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_donations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('donor_id');
            $table->integer('amount');
            $table->date('next_donation_on');
            $table->boolean('is_paying_fees')->default(false);
            $table->string('designation_id');
            $table->string('mission_support')->nullable()->default(null);
            $table->string('staff_support')->nullable()->default(null);
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
        Schema::dropIfExists('recurring_donations');
    }
}
