<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
	public function up()
	{
		Schema::create("post",function (Blueprint $table){
            $table->id();
            $table->bigInteger('author_id')->unsigned()->index();
            $table->bigInteger('viewCount')->default(0)->index();
            $table->boolean('isActive')->default(false);

            $table->timestamp('publish_at')->nullable();

            $table->timestamps();
		    $table->softDeletesTz();

            $table->foreign('author_id')->references('id')
                ->onDelete('cascade')
                ->on('users');
        });
	}


	public function down()
	{
		Schema::dropIfExists("post");
	}
}
