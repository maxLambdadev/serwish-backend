<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacketToServiceTable extends Migration
{
	public function up()
	{
		Schema::create("packet_to_service",function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('payable_packet_id');

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

            $table->foreign('service_id')
                ->references('id')
                ->onDelete('cascade')
                ->on('services');

            $table->foreign('user_id')
                ->references('id')
                ->onDelete('cascade')
                ->on('users');

            $table->foreign('payable_packet_id')
                ->references('id')
                ->onDelete('cascade')
                ->on('payablepacket');

            $table->timestamps();

        });
	}

	public function down()
	{
		Schema::dropIfExists("packet_to_service");
	}
}
