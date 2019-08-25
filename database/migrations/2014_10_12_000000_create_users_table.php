<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id',50);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('city');
            $table->string('mobile_no');
            $table->integer('is_verified')->default(0);
            $table->string('profession');
            $table->string('password');
            $table->string('bcrypt_password');
            $table->string('encryptpass');
            $table->string('type',50)->default('');
            $table->enum('status', ['Active', 'Inactive']);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
