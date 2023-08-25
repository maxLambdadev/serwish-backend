<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewFieldsToservicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services',function (Blueprint $table){
            //review status started: in_review, published
            $table->string('review_status')->default('started');
            $table->bigInteger('reviewer_id')->unsigned()->nullable();
            $table->foreign("reviewer_id")->references('id')
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
        Schema::table('services',function (Blueprint $table){
            $table->dropColumn('reviewer_id');
            $table->dropColumn('review_status');
        });
    }
}
