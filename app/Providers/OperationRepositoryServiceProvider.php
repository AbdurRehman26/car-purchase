<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Data\Models\Operation;
use App\Data\Repositories\OperationRepository;

class OperationRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('OperationRepository', function () {
            return new OperationRepository(new Operation);
        });
    }
}
