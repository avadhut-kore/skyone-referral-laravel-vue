<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',56);
            $table->text('description',256);
            $table->string('image',56)->nullable();
            $table->integer('category_id');
            $table->integer('is_active')->default(1);
            $table->integer('reward_type_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('reward_type_id')->references('id')->on('reward_types');
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
        Schema::dropIfExists('products');
    }
}
