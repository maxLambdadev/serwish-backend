<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCityTranslationsTable extends Migration
{
	public function up()
	{
		Schema::create("city_translations",function (Blueprint $table){
            $table->id();
            $table->timestamps();
            $table->string('name')->nullable();
            $table->json('meta')->nullable();
            $table->string('locale')->index();
            $table->bigInteger('city_id')->unsigned();
            $table->foreign("city_id")->references('id')
                ->onDelete('cascade')
                ->on("city");
        });
	}


	public function down()
	{
		Schema::dropIfExists("city_translations");
	}
}
