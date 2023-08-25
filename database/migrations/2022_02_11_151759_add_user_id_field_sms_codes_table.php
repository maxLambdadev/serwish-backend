<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdFieldSmsCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms_codes', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned(true)->index()->nullable();

            $table->foreign('user_id')->references('id')
                ->onDelete('cascade')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_codes', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
