# Laravel-Module-Auth

This module supports authentication backend for Angular-Module-Auth

**Required packages**
1. "tymon/jwt-auth": "^1.0.0-rc.4.1"

**Required Modules**
1. Laravel-Module-Core

**Functionalities**
1. Login
2. Logout
3. Register

**Installation**
1. Add the module to Laravel project as a submodule. 
`git submodule add https://github.com/bwqr/Laravel-Module-Auth app/Modules/Auth`
2. Add the route file `Http/auth.php` to `app/Providers/RouteServiceProvider.php`
 and register inside the `map` function, eg.  
 `
    protected function mapAuthRoutes()
    {
        Route::prefix('auth')
            ->middleware('api')
            ->namespace($this->moduleNamespace . "\Auth\Http\Controllers")
            ->group(base_path('app/Modules/Auth/Http/auth.php'));
    }
 `
