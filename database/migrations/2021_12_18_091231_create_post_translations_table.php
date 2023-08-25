<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTranslationsTable extends Migration
{
	public function up()
	{
		Schema::create("post_translations",function (Blueprint $table){
            $table->id();
            $table->timestamps();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->json('meta')->nullable();

            $table->string('locale')->index();
            $table->bigInteger('post_id')->unsigned();
            $table->foreign("post_id")->references('id')
                ->onDelete('cascade')
                ->on("post");

        });
	}


	public function down()
	{
		Schema::dropIfExists("post_translations");
	}
}
