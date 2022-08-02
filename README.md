## Properos Users

CRUD package.

**Required properos/properos-base package**
**Required laravel/socialite package if want to auth with social accounts**
Configuration => https://laravel.com/docs/5.6/socialite

**Added on config/services.php**
```php
    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID', '147107342556627'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', 'eda29465e6a4a98b5f89a1c2a3829f14'),
        'redirect' => env('FACEBOOK_CALLBACK','http://properos.com/auth/facebook/callback')
    ],
    
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', '200207622942-nadc5euejp1fb7jj1m13fdhu30ot4icc.apps.googleusercontent.com'),
        'client_secret' => env('GOGOLE_CLIENT_SECRET', 'uhIvqdMkWVD43Lw9EvvsMfQf'),
        'redirect' => env('GOOGLE_CALLBACK','http://properos.com/auth/google/callback')
        
    ]
```
**Added on config/app.php**
```php
    Laravel\Socialite\SocialiteServiceProvider::class,
```

**Modify config/database.php**
```php
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => 'Innodb',
    ],
```

**Required spatie/laravel-permission package**
**IMPORTANT NOTE: If you want to use restrictable_type and restrictable_id you have to use "spatie/laravel-permission": "^2.2.0" and add the follow code before install**
**You need to use restrictable_type and restrictable_id with properos-condo package and you have to uncomment the code refers to units (CreateUserComponent.vue and UserController.php -> editUser()**

**Add repositories on composer.json**
```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/IlCallo/laravel-permission"
        }
    ],
```
**If not you can install spatie with the code below**
composer require spatie/laravel-permission

Configuration => https://docs.spatie.be/laravel-permission/v3/installation-laravel/

**Register middleware app/Http/Kernel.php**
**If use restrictable**
```php
    'role' => \Properos\Users\Middleware\RoleMiddleware::class,
```
**If not you have to register:**
```php
    'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
    'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class
```

**Required moment.js**
npm install moment 

**Add on config/app.php**
```php
    'providers' => [
        '...',
        Properos\Users\UsersServiceProvider::class,
        '...'
    ]
```

**Register provider on composer.json**
```json
    "autoload": {
    "...": {},
        "psr-4": {
            "App\\": "app/",
            "Properos\\Users\\": "packages/properos/properos-users-advanced/src"
        }
    },
```

**Run**
    composer dump
    php artisan vendor:publish 
Select -> Properos\Users\UsersServiceProvider  

**Create env.js**

**Add on webpack.mix.js**
.js('resources/assets/js/be/modules/users/js/user.js', 'public/be/js/modules/user.js')

**Add on resources/assets/bootstrap.js if not exist**
```js
    import Helpers from './misc/helpers'

    window.moment = require('moment')
    window.Vue = require('vue');
    window.Helpers = Helpers;
```

**config/properos_users.php file**
Set the middleware for the routes.

**How to use a Model**
\Properos\Users\Models\Model-Name

**Modify config/auth.php**
```php
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \Properos\Users\Models\User::class,
        ],
    ],
``` 

**Run migrations**
php artisan migrate
    create  users table
            user_addresses table
            user_profiles table
    modify  roles table

**Add seeder on database/seeds/DatabaseSeeder.php**
```php
    Set all roles on RolesPermissionsTableSeeder and users on UsersTableSeeder
    $this->call(RolesPermissionsTableSeeder::class);
    $this->call(UsersTableSeeder::class);
```
Run composer dump-autoload
php artisan db:seed
npm run watch

**Use API authentication**
    Install laravel/passport package => https://laravel.com/docs/5.6/passport

    ```php
        Descomment 
        use Laravel\Passport\HasApiTokens;
        use HasApiTokens;
        in \Models\Users.php
    ```
    
**Add on routes/web.php**
```php
    Route::get('/admin/dashboard', function(){
        return view('be.index');
    })->middleware(['auth', 'role:admin']);
```




