<?php

namespace Thinklar\PrefixedPermission;

use Illuminate\Support\ServiceProvider;
use Thinklar\PrefixedPermission\Commands\InstallPrefixedPermission;

class PrefixedPermissionServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallPrefixedPermission::class,
            ]);
        }
    }
}
