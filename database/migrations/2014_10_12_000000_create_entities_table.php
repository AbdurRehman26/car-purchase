<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEntitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('entities', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('title')->default('');
			$table->timestamps();
			$table->softDeletes();
			$table->boolean('isGeneral')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('entities');
	}

}
