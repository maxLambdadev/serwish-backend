<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

class CreateServicesTranslationsTable extends Migration
{
	public function up()
	{
		Schema::create("services_translations",function (Blueprint $table){
		               $table->id();
		               $table->timestamps();
                       $table->string('title')->nullable();
                       $table->longText('description')->nullable();
                       $table->json('extra_data')->nullable();
		               $table->string('locale')->index();
		               $table->bigInteger('services_id')->unsigned();
		               $table->foreign("services_id")->references('id')
		               ->onDelete('cascade')
		               ->on("services");
		            });
	}


	public function down()
	{
		Schema::dropIfExists("services_translations");
	}
}
