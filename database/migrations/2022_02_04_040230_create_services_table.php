<?php use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
	public function up()
	{
		Schema::create("services",function (Blueprint $table){
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->double('price',8,2)->default(0.0);
            $table->string('contact_number')->nullable();
            $table->boolean('has_online_payment')->default(false);
            $table->boolean('has_serwish_quality')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('publish_at')->nullable();
            $table->integer('buttonClicked')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')
                ->onDelete('cascade')
                ->on('users');
        });
	}


	public function down()
	{
		Schema::dropIfExists("services");
	}
}
