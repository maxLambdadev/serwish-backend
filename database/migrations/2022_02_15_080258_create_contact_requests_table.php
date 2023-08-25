<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactRequestsTable extends Migration
{
	public function up()
	{
		Schema::create("contact_requests",function (Blueprint $table){
            $table->id();
            $table->boolean('seen')->default(false);
            $table->string('phone');
            $table->string('title');
            $table->longText('description');
            $table->string('subject')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::dropIfExists("contact_requests");
	}
}
