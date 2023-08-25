<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTranslationsTable extends Migration
{
	public function up()
	{
		Schema::create("menu_translations",function (Blueprint $table){
		               $table->id();
		               $table->timestamps();
                       $table->string('name')->nullable();
                       $table->string('href')->nullable();
                       $table->jsonb('extra')->nullable();
		               $table->string('locale')->index();
		               $table->bigInteger('menu_id')->unsigned();
		               $table->foreign("menu_id")->references('id')
		               ->onDelete('cascade')
		               ->on("menu");
		            });
	}


	public function down()
	{
		Schema::dropIfExists("menu_translations");
	}
}
