<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacketsTranslationsTables extends Migration
{
	public function up()
	{
		Schema::create("payable_packets_translations",function (Blueprint $table){
            $table->id();
            $table->string('name')->index()->nullable();
            $table->longText('description')->nullable();

            $table->timestamps();
            $table->string('locale')->index();
            $table->integer('payablepacket_id')->unsigned();

            $table->foreign("payablepacket_id")
                ->references('id')
                ->onDelete('cascade')
                ->on("payablepacket");
        });

	}


	public function down()
	{
		Schema::dropIfExists("payable_packets_translations");
	}
}
