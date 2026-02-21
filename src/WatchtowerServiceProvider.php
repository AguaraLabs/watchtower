<?php

namespace Aguaralabs\Watchtower;

use Auth;
use Config;
use Exception;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Aguaralabs\Watchtower\Models\Permission;

class WatchtowerServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // load the views
        $this->loadViewsFrom(__DIR__ . '/Views', 'watchtower');

        // Publishes package files
        $this->publishes([
            __DIR__ . '/Config/watchtower.php' => config_path('watchtower.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/Config/watchtower-menu.php' => config_path('watchtower-menu.php')
        ], 'config-menu');

        $this->publishes([
            __DIR__ . '/Views' => base_path('resources/views/vendor/watchtower')
        ], 'views');

        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }

        // 2. View Composer (Movido de register a boot por seguridad)
        $this->app['view']->composer('*', function ($view) {
            $user = Auth::user();
            $defaultTheme = $this->app['config']->get('watchtower.default_theme');

            $view->theme = ($user && isset($user->theme)) ? $user->theme : $defaultTheme;
            $view->title = $this->app['config']->get('watchtower.site_title');
        });

        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                return true;
            }
        });

        $router = $this->app['router'];
        $router->aliasMiddleware('watchtower.can', \Aguaralabs\Watchtower\Middleware\WatchtowerPermissionMiddleware::class);

        $this->registerDynamicGates();
    }

    protected function registerDynamicGates()
    {
        try {
            if ($this->app->runningInConsole() && !Schema::hasTable('permissions')) {
                return;
            }

            /*Permission::select('slug')->chunk(100, function ($permissions) {
                foreach ($permissions as $permission) {
                    Gate::define($permission->slug, function ($user) use ($permission) {
                        return method_exists($user, 'hasPermission')
                            ? $user->hasPermission($permission->slug)
                            : false;
                    });
                }
            });*/

            Permission::pluck('slug')->each(function ($slug) {
                Gate::define($slug, function ($user) use ($slug) {
                    return method_exists($user, 'hasPermission')
                        ? $user->hasPermission($slug)
                        : false;
                });
            });
        } catch (Exception $e) {

            return;
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge config files
        $this->mergeConfigFrom(__DIR__ . '/Config/watchtower.php', 'watchtower');
        $this->mergeConfigFrom(__DIR__ . '/Config/watchtower-menu.php', 'watchtower-menu');

        // Register it
        $this->app->bind('watchtower', function ($app) {
            return new Watchtower;
        });
    }
}
