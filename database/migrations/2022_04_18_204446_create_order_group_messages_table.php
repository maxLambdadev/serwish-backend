<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderGroupMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_group_messages', function (Blueprint $table) {
            $table->id();

            $table->longText('message');
            $table->enum('type',['message','change_state','payment_request','half_payment_request',])->default('message');

            $table->boolean('seen')->default(false);
            $table->enum('sender',['customer','specialist'])->default('customer');

            $table->bigInteger('order_groups_id')->unsigned()->index();
            $table->foreign('order_groups_id')->references('id')
                ->onDelete('cascade')
                ->on('service_order_groups');

            $table->bigInteger('sender_id')->unsigned()->index();
            $table->foreign('sender_id')->references('id')
                ->onDelete('cascade')
                ->on('users');

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
        Schema::dropIfExists('order_group_messages');
    }
}
