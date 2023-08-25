<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToOrderGroupMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_group_messages', function (Blueprint $table) {
            $table->string('status')->default('open');
            $table->string('payment_status')->default('open');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_group_messages', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('payment_status');
        });
    }
}
