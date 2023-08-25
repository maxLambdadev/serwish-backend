<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
	public function up()
	{
		Schema::create("teams",function (Blueprint $table){
            $table->id();
            $table->longText('meta')->nullable();

            $table->boolean('isActive')->default(false);
            $table->timestamps();
        });
	}


	public function down()
	{
		Schema::dropIfExists("teams");
	}
}
