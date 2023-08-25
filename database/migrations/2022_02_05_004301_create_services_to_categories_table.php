<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_to_categories', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('services_id')->unsigned()->index();
            $table->bigInteger('category_id')->unsigned()->index();

            $table->foreign('services_id')->references('id')
                ->onDelete('cascade')
                ->on('services');
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
        Schema::dropIfExists('services_to_categories');
    }
}
