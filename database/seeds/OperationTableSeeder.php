<?php

use Illuminate\Database\Seeder;
use App\Data\Models\Entity;
use App\Data\Models\Operation;
use Carbon\Carbon;

class OperationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('operations')->truncate();
        
        $now = Carbon::now()->toDateTimeString();

        $crudOperations = ['Store', 'Show', 'Update', 'Destroy', 'Review'];

        $entities = app('EntityRepository')->findByAll();        

        foreach ($entities['data'] as $key => $entity) {
          $key = $entity->id;

          $create = [
             'entity_id' => $key,
             'title' => str_replace('Store' , 'Create' , $crudOperations[0]).' '.$entity->title,
             'route' => strtolower(str_replace(' ' , '-' , $entity->title) .'.'. $crudOperations[0]),
             'method' => 'POST',
             'required_operations' => json_encode(['show']),
             'created_at' => $now,
             'updated_at' => $now

         ];

         $view = [
            'entity_id' => $key,
            'title' => str_replace('Show' , 'View' , $crudOperations[1]).' '.$entity->title,
            'route' => strtolower(str_replace(' ' , '-' , $entity->title) .'.'. $crudOperations[1]),
            'method' => 'GET', 'required_operations' => '',
            'created_at' => $now,
            'updated_at' => $now

        ];

        $update = [
         'entity_id' => $key,
         'title' => $crudOperations[2].' '.$entity->title, 
         'route' => strtolower(str_replace(' ' , '-' , $entity->title) .'.'. $crudOperations[2]),
         'method' => 'PUT', 'required_operations' => json_encode(['show', 'store']),
         'created_at' => $now,
         'updated_at' => $now

     ];

     $delete = [
        'entity_id' => $key,
        'title' => str_replace('Destroy' , 'Delete' , $crudOperations[3]).' '.$entity->title,
        'route' => strtolower(str_replace(' ' , '-' , $entity->title) .'.'. $crudOperations[3]),
        'method' => 'POST', 'required_operations' => json_encode(['show', 'store', 'update']),
        'created_at' => $now,
        'updated_at' => $now

    ];

    if($entity->title== 'Data Importer') {

        $review = [
            'entity_id' => $key,
            'title' => $crudOperations[4].' '.$entity->title,
            'route' => strtolower(str_replace(' ' , '-' , $entity->title) .'.'. $crudOperations[4]),
            'method' => 'POST', 'required_operations' => json_encode(['show', 'store', 'update']),
            'created_at' => $now,
            'updated_at' => $now
            
        ];

        $inputArray[] = $review;
    }
    $inputArray[] = $view;
    $inputArray[] = $create;
    $inputArray[] = $update;
    $inputArray[] = $delete;
}

DB::statement('SET FOREIGN_KEY_CHECKS=1;');
Operation::insert($inputArray);

echo "\n Operation table seeder ran successfully.\n";

}
}
