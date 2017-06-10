<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('donor_id');
            $table->boolean('is_paying_fees')->default(false);
            $table->unsignedInteger('designation_id');
            $table->text('general_comments')->nullable()->default(null);
            $table->text('mission_support')->nullable()->default(null);
            $table->text('staff_support')->nullable()->default(null);
            $table->boolean('is_recurring')->default(false);
            $table->integer('amount');
            $table->timestamps();

            $table->index('donor_id');
            $table->index('designation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donations');
    }
}
