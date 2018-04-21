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
            $table->increments('id');
            $table->string('fname', 50);
            $table->string('name', 50);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('address')->nullable();
            $table->integer('city_id')->unsigned()->nullable();
            $table->foreign('city_id')
                ->references('id')->on('city')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('country', 70)->default('België');
            $table->string('picture', 100)->nullable();
            $table->string('picture_thumb', 100)->nullable();
            $table->string('introduction', 300)->nullable();
            $table->enum('role',['admin','user','service'])->default('user');
            $table->boolean('is_verified')->default(0);
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
