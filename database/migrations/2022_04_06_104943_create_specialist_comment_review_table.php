<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialistCommentReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialist_comment_review', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('locale')->default('ka');
            $table->longText('description')->default('');
            $table->boolean('likes');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign("user_id")->references('id')
                ->onDelete('cascade')
                ->on("users");

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
        Schema::dropIfExists('specialist_comment_review');
    }
}
