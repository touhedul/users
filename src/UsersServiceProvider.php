<?php

namespace Properos\Users;

use Illuminate\Support\ServiceProvider;

class UsersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/properos_users.php' => config_path('properos_users.php'),
        ]);

        $this->loadRoutesFrom(__DIR__.'/user-routes.php');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->publishes([
            __DIR__.'/resources/assets' => resource_path('assets/js/be/modules/users'),
        ], 'users');

        $this->publishes([
            __DIR__.'/resources/views/index.blade.php' => resource_path('views/be/users/index.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/create-user.blade.php' => resource_path('views/be/users/create-user.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/my-profile.blade.php' => resource_path('views/be/users/my-profile.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/layouts/menu' => resource_path('views/be/layouts/menu'),
        ]);

        $this->publishes([
            __DIR__.'/seeds' => base_path('database/seeds'),
        ]);
        
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Properos\Users\Controllers\UserController');

    }
}
