<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSliderTable extends Migration
{
	public function up()
	{
		Schema::create("slider",function (Blueprint $table){
            $table->id();
            $table->integer('sort_order')->default(0);
            $table->boolean('isActive')->default(false);
            $table->timestamps();
        });
	}


	public function down()
	{
		Schema::dropIfExists("slider");
	}
}
