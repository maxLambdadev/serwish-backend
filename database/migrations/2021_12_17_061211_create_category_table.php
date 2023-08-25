<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTable extends Migration
{
	public function up()
	{
		Schema::create("category",function (Blueprint $table){
		                    $table->id();
                            $table->bigInteger('author_id')->unsigned()->index();
                            $table->boolean('isActive')->default(false);
                            $table->string('type')->default('POST')->index();
		                    $table->timestamps();
                            $table->softDeletes();

                            $table->foreign('author_id')->references('id')
                                ->onDelete('cascade')
                                ->on('users');
        });
	}


	public function down()
	{
		Schema::dropIfExists("category");
	}
}
