<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    //

	public $searchables = [
		'shipped_record',
		'local_or_state',
		'funding_status',
		'cash_finance',
		'sales_status',
		'location',
	];
}
