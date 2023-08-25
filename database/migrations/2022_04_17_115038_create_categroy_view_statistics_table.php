<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategroyViewStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categroy_view_statistics', function (Blueprint $table) {
            $table->id();

            //we need view_incr because of needed category view statistic by date
            $table->integer('view_incr')->default(1);
            //feature other fields

            $table->bigInteger('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')
                ->onDelete('cascade')
                ->on('category');


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
        Schema::dropIfExists('categroy_view_statistics');
    }
}
