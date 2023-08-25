<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceOrderGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_order_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('room_state',['started','in_progress','half_payment_requested','half_payment_approved','payment_finished','order_closed']);


            $table->bigInteger('customer_id')->unsigned()->index();
            $table->foreign('customer_id')->references('id')
                ->onDelete('cascade')
                ->on('users');

            $table->bigInteger('specialist_id')->unsigned()->index();
            $table->foreign('specialist_id')->references('id')
                ->onDelete('cascade')
                ->on('users');

            $table->bigInteger('service_id')->unsigned()->index();
            $table->foreign('service_id')->references('id')
                ->onDelete('cascade')
                ->on('services');

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
        Schema::dropIfExists('service_order_groups');
    }
}
