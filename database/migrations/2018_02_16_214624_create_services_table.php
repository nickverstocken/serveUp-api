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
            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('subcategory_id')->unsigned()->nullable();
            $table->foreign('subcategory_id')
                ->references('id')->on('sub_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
            $table->text('social_networks')->nullable();
            $table->string('logo', 100)->nullable();
            $table->string('banner', 100)->nullable();
            $table->string('business_hours', 300)->nullable();
            $table->text('areas_of_service')->nullable();
            $table->integer('max_km')->default(5);
            $table->double('price_estimate', 12, 2);
            $table->string('rate');
            $table->text('price_extras')->nullable();
            $table->text('faq')->nullable();
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
