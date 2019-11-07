<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Data\Models\Entity;
use App\Data\Repositories\EntityRepository;

class EntityRepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind('EntityRepository', function () {
            return new EntityRepository(new Entity);
        });
    }
}
