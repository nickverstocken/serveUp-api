<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('name', 50);
            $table->text('description');
            $table->string('address');
            $table->integer('city_id')->unsigned();
            $table->foreign('city_id')
                ->references('id')->on('city')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('country', 70)->default('België');
            $table->string('tel', 20)->nullable();
            $table->integer('experience')->default(0);
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('google')->nullable();
            $table->string('pinterest')->nullable();
            $table->string('instagram')->nullable();
            $table->string('snapchat')->nullable();
            $table->string('dribble')->nullable();
            $table->string('behance')->nullable();
            $table->string('logo', 100)->nullable();
            $table->string('banner', 100)->nullable();
            $table->string('business_hours', 300)->nullable();
            $table->string('areas_of_service', 300)->nullable();
            $table->integer('max_km')->nullable();
            $table->double('price_estimate', 12, 2);
            $table->string('rate');
            $table->string('price_extras')->nullable();
            $table->string('standard_response', 7000)->nullable();
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
        Schema::dropIfExists('services');
    }
}
