<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSliderTranslationsTable extends Migration
{
	public function up()
	{
		Schema::create("slider_translations",function (Blueprint $table){
            $table->id();
            $table->timestamps();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('locale')->index();
            $table->bigInteger('slider_id')->unsigned();
            $table->foreign("slider_id")->references('id')
                ->onDelete('cascade')
                ->on("slider");
        });
	}


	public function down()
	{
		Schema::dropIfExists("slider_translations");
	}
}
