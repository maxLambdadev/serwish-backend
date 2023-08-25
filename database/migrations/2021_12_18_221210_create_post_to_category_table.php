<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostToCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_to_category', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('post_id')->unsigned()->index();
            $table->bigInteger('category_id')->unsigned()->index();

            $table->foreign('post_id')->references('id')
                ->onDelete('cascade')
                ->on('post');
            $table->foreign('category_id')->references('id')
                ->onDelete('cascade')
                ->on('category');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_to_category');
    }
}
