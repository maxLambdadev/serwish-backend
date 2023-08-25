<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaDataToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('gender',['male','female','other','custom','notsay'])->nullable();
            $table->dateTime('date_of_birth')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('id_number')->nullable();
            $table->enum('personal',['personal','business'])->default('personal');
            $table->enum('client_type',['client','employee'])->default('client');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('phone_number');
            $table->dropColumn('id_number');
            $table->dropColumn('personal');
            $table->dropColumn('client_type');
        });
    }
}
