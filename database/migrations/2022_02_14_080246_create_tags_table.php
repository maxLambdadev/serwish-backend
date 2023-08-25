<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
	public function up()
	{
		Schema::create("tags",function (Blueprint $table){
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->bigInteger('user_id')->unsigned()->index();

            $table->timestamps();

            $table->foreign('user_id')->references('id')
                ->onDelete('cascade')
                ->on('users');
        });
	}


	public function down()
	{
		Schema::dropIfExists("tags");
	}
}
