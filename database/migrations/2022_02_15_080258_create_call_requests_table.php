<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallRequestsTable extends Migration
{
	public function up()
	{
		Schema::create("call_requests",function (Blueprint $table){
            $table->id();
            $table->boolean('is_called')->default(false);
            $table->bigInteger('category_id');
            $table->string('phone_number');
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::dropIfExists("call_requests");
	}
}
