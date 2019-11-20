<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Data\Models\Entity;

class EntityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('entities')->truncate();

    	$inputArray = [
        'trade_in' , 
        'need_to_address',
        'trade_in',
        'deposit',
        'down_payment',
        'local_or_state',
        'cash_finance',
        'location',
        'shipped',
        'signed_record',
        'parts_needed',
        'inspection',
        'make_ready',
        'lender',
        'funding_status',
        'repair_status',
        ];
    	
        // $generalInputArray = [ 'User' , 'Role' , 'Configuration' , 'Brands'];

    	$now = Carbon::now()->toDateTimeString();

    	foreach ($inputArray as $value) { 
    		$input[] = ['title' =>  $value , 'created_at' => $now , 'updated_at' => $now , 'isGeneral' => null];
    	}

        // foreach ($generalInputArray as $value) { 
        //     $input[] = ['title' =>  $value , 'created_at' => $now , 'updated_at' => $now , 'isGeneral' => 1];
        // }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    	Entity::insert($input);

    	echo "\nEntity table seeder ran successfully.\n";
    }
}
