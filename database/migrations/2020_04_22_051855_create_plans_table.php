<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));

            $table->enum('risk', ['low', 'high', 'neutral'])->nullable()->default('neutral');
            $table->enum('scope', ['root', 'immediate', 'neutral'])->nullable()->default('netural');
            $table->enum('scale', ['small', 'large', 'neutral'])->nullable()->default('neutral');


            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->uuid('plan_id')->nullable();
            $table->foreign('plan_id')->references('id')->on('plans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('plan_id');
            $table->dropColumn('plan_id');
        });
        Schema::dropIfExists('plans');
    }
}
