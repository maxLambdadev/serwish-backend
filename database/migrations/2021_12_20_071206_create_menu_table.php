<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
	public function up()
	{
		Schema::create("menu",function (Blueprint $table){
		                    $table->id();
                            $table->bigInteger('parent_id')->nullable()->unsigned()->index();
                            $table->string('type')->default('menu')->index();
                            $table->boolean('isActive')->default(false);
		                    $table->timestamps();
                            $table->softDeletes();

                            $table->foreign('parent_id')->references('id')
                                ->onDelete('cascade')
                                ->on('menu');
		                });
	}


	public function down()
	{
		Schema::dropIfExists("menu");
	}
}
