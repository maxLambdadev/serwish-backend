<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_working_hours', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('service_id')->unsigned()->index();
            $table->string('type');
            $table->integer('week_day')->nullable();
            $table->string('start_at')->nullable();
            $table->string('end_at')->nullable();
            $table->string('saturday_start_at')->nullable();
            $table->string('saturday_end_at')->nullable();
            $table->string('sunday_start_at')->nullable();
            $table->string('sunday_end_at')->nullable();

            $table->foreign('service_id')
                ->references('id')
                ->onDelete('cascade')
                ->on('services');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_working_hours');
    }
}
