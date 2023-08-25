<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTranslationsTable extends Migration
{
	public function up()
	{
		Schema::create("category_translations",function (Blueprint $table){
            $table->id();
            $table->timestamps();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->json('meta')->nullable();
            $table->string('locale')->index();
            $table->bigInteger('category_id')->unsigned();
            $table->foreign("category_id")->references('id')
                ->onDelete('cascade')
                ->on("category");
        });
	}


	public function down()
	{
		Schema::dropIfExists("category_translations");
	}
}
