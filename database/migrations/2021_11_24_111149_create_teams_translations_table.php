<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTranslationsTable extends Migration
{
	public function up()
	{
		Schema::create("teams_translations",function (Blueprint $table){
            $table->id();

            $table->string('name')->index()->nullable();
            $table->longText('description')->nullable();

            $table->timestamps();
            $table->string('locale')->index();
            $table->bigInteger('teams_id')->unsigned();
            $table->foreign("teams_id")
                ->references('id')
                ->onDelete('cascade')
                ->on("teams");
        });
	}


	public function down()
	{
		Schema::dropIfExists("teams_translations");
	}
}
