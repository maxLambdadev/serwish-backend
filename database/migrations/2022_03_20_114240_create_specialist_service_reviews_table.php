<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialistServiceReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialist_service_reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('value')->index();
            $table->string('extra')->nullable();
            $table->timestamps();

            $table->bigInteger('service_id')->unsigned();
            $table->foreign("service_id")->references('id')
                ->onDelete('cascade')
                ->on("services");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specialist_service_reviews');
    }
}
