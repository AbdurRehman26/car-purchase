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

            'need_to_address',
            'trade_in',
            'deposit',
            'down_payment',
            'local_or_state',
            'cash_finance',
            'location',
            'shipped',
            'signed_record',
            'lender',
            'funding_status',
            'warranty',
            'parts_needed',
            'inspection',
            'make_ready',
            'repair_status',
            'ship_date',
            'notes',
            'docs_needy',
            'review',
            'sales_status',

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
