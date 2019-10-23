<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Data\Models\Purchase;
use App\Data\Repositories\PurchaseRepository;

class PurchaseRepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind('PurchaseRepository', function () {
            return new PurchaseRepository(new Purchase);
        });
    }
}
