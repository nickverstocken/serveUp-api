<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('accepted')->default(0);
            $table->enum('status',['awaiting','accepted','declined', 'not_hired'])->default('awaiting');
            $table->boolean('hired')->default(0);
            $table->double('price_offer', 12, 2)->nullable();
            $table->string('rate')->nullable();
            $table->integer('request_id')->unsigned();
            $table->foreign('request_id')
                ->references('id')->on('requests')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('service_id')->unsigned();
            $table->foreign('service_id')
                ->references('id')->on('services')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('offers');
    }
}