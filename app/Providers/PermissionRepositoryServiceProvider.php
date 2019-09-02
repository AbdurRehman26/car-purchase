<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Data\Models\Permission;
use App\Data\Repositories\PermissionRepository;

class PermissionRepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind('PermissionRepository', function () {
            return new PermissionRepository(new Permission);
        });
    }
}
