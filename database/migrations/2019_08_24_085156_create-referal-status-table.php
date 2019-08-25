<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferalStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('referal_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('referal_id');
            $table->integer('is_contacted')->default(0);
            $table->integer('is_interested')->default(0);
            $table->integer('is_purchased')->default(0);
            $table->integer('is_referal_rewarded')->default(0);
            $table->integer('is_refered_by_rewarded')->default(0);
            $table->integer('referal_reward_type');
            $table->integer('refered_by_reward_type');
            $table->double('referal_reward_amount');
            $table->double('refered_by_reward_amount');
            $table->foreign('referal_id')->references('id')->on('referals');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referal_statuses');
    }
}
