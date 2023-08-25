<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTranslationsTable extends Migration
{
	public function up()
	{
		Schema::create("tags_translations",function (Blueprint $table){
		               $table->id();
		               $table->timestamps();
                       $table->string('name')->nullable();
                       $table->json('meta')->nullable();
		               $table->string('locale')->index();
		               $table->bigInteger('tags_id')->unsigned();
		               $table->foreign("tags_id")->references('id')
		               ->onDelete('cascade')
		               ->on("tags");
        });
	}


	public function down()
	{
		Schema::dropIfExists("tags_translations");
	}
}
