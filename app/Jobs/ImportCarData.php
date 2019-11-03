<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Imports\CarDataImport;
use Maatwebsite\Excel\Facades\Excel;


class ImportCarData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fileName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        $fileName = $this->fileName;

        $array = Excel::toArray(new CarDataImport, storage_path('app/public/'.$fileName));

        $input = [];

        foreach ($array[0] as $key => $row) {
            
            if($key){

            $input[] = [
                'vin' => $row[0],
                'year'=> $row[1],
                'make' => $row[2],
                'model' => $row[3]
            ];

            }            
        }

        app('PurchaseRepository')->insertMultiple($input);


    }
}
