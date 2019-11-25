<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchases', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->string('vin')->nullable();
			$table->string('model')->nullable();
			$table->integer('year')->nullable();
			$table->string('make')->nullable();
			$table->text('need_to_address', 65535)->nullable();
			$table->text('trade_in', 65535)->nullable();
			$table->text('deposit', 65535)->nullable();
			$table->text('down_payment', 65535)->nullable();
			$table->enum('local_or_state', array('local','state'))->nullable();
			$table->enum('cash_finance', array('cash','finance'))->nullable();
			$table->enum('location', array('gone','lot','on_lot'))->nullable();
			$table->enum('shipped', array('pu','ship_cop','ship_cod','picked_up','pick_up'))->nullable();
			$table->enum('signed_record', array('yes','no'))->nullable();
			$table->integer('lender')->nullable();
			$table->enum('funding_status', array('not','financed','wired','cashier','draft'))->nullable();
			$table->integer('warranty')->nullable();
			$table->text('parts_needed', 65535)->nullable();
			$table->text('review', 65535)->nullable();
			$table->enum('inspection', array('yes','no','n/a'))->nullable();
			$table->integer('make_ready')->nullable();
			$table->text('notes')->nullable();
			$table->text('repair_status', 65535)->nullable();
			$table->boolean('is_sold')->nullable();
			$table->date('file_uploaded_at')->nullable();
			$table->date('ship_date')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('purchases');
	}

}
