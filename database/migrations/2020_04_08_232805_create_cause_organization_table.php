<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCauseOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cause_organization', function (Blueprint $table) {
            $table->id();

            $table->uuid('cause_id');
            $table->foreign('cause_id')->references('id')->on('causes');

            $table->uuid('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations');

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
        Schema::dropIfExists('cause_inclusion');
    }
}
