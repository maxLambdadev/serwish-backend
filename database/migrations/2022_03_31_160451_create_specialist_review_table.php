<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialistReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialist_review', function (Blueprint $table) {
            $table->id();
            $table->integer('value')->index();
            $table->string('extra')->nullable();
            $table->timestamps();

            $table->bigInteger('user_id')->unsigned();
            $table->foreign("user_id")->references('id')
                ->onDelete('cascade')
                ->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specialist_review');
    }
}
