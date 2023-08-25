<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderGroupPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_group_payments', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('started');
            $table->string('amount');
            $table->enum('capture_method',['AUTOMATIC','MANUAL'])->default('AUTOMATIC');
            $table->string('order_id')->nullable();
            $table->string('shop_order_id')->nullable();
            $table->boolean('show_shop_order_id_on_extract')->default(true);
            $table->string('redirect_url')->nullable();
            $table->string('intent')->nullable();
            $table->string('payment_hash')->nullable();
            $table->string('locale')->default('ka');

            $table->bigInteger('order_groups_id')->unsigned()->index();
            $table->foreign('order_groups_id')->references('id')
                ->onDelete('cascade')
                ->on('service_order_groups');

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
        Schema::dropIfExists('order_group_payments');
    }
}
