<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqTranslationsTable extends Migration
{
	public function up()
	{
		Schema::create("faq_translations",function (Blueprint $table){
            $table->id();
            $table->timestamps();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('button_link')->nullable();
            $table->string('locale')->index();
            $table->bigInteger('faq_id')->unsigned();
            $table->foreign("faq_id")->references('id')
                ->onDelete('cascade')
                ->on("faq");
        });
	}


	public function down()
	{
		Schema::dropIfExists("faq_translations");
	}
}
