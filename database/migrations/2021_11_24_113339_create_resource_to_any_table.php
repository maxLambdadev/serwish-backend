<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceToAnyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_to_any', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('resource_id')->unsigned();
            $table->bigInteger('other_id')->unsigned();
            $table->string('other_entity')->index();

            $table->string('meta')->nullable();
            $table->boolean('is_active')->default(true)->index();

            $table->foreign('resource_id')
                ->references('id')
                ->onDelete('cascade')
                ->on('resources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resource_to_any');
    }
}
