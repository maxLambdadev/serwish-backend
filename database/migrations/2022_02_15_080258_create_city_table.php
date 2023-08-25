<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCityTable extends Migration
{
	public function up()
	{
		Schema::create("city",function (Blueprint $table){
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
	}


	public function down()
	{
		Schema::dropIfExists("city");
	}
}
