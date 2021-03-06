<?php namespace SmallTeam\Dashboard;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use SmallTeam\Dashboard\Routing\RoutesMapper;

class ServiceProvider extends BaseServiceProvider
{

    public function boot()
    {
        $path_to_assets = __DIR__ . '/public';
        $path_to_views = __DIR__ . '/resources/views';
        $path_to_translations = __DIR__ . '/resources/lang';

        $locales = config('dashboard.locales');

        $this->loadViewsFrom($path_to_views, 'dashboard');

        $this->publishes([
            $path_to_views => base_path('resources/views/vendor/dashboard'),
        ]);

        $this->loadTranslationsFrom($path_to_translations, 'dashboard');

        if (is_array($locales) && count($locales) > 0) {
            foreach ($locales as $locale) {
                if (!is_file($path_to_translations . '/' . $locale)) {
                    continue;
                }

                $this->publishes([
                    $path_to_translations . '/' . $locale => base_path('resources/lang/packages/' . $locale . '/dashboard'),
                ]);
            }
        }

        $this->publishes([
            __DIR__ . '/config/dashboard.php' => config_path('dashboard.php'),
        ]);

        $this->publishes([
            $path_to_assets => public_path('vendor/dashboard'),
        ], 'public');

        /** Register dashboard middlewares */
        $router = $this->app['router'];
        $router->middleware('dashboard.auth', 'SmallTeam\Dashboard\Middleware\Authenticate');
        $router->middleware('dashboard.guest', 'SmallTeam\Dashboard\Middleware\Guest');

        $dashboards = config('dashboard.dashboards');

        foreach ($dashboards as $dashboard_alias => $dashboard) {
            if (!isset($dashboard['entities']['index'])) {
                $dashboard['entities'] = array_merge(['index' => \SmallTeam\Dashboard\Entity\DashboardEntity::class], $dashboard['entities']);
            }

            foreach ($dashboard['entities'] as $entity_name => $entity_class) {
                $service = 'dashboard.' . $dashboard_alias . '.' . $entity_name;

                $entity = app($entity_class);

                app()->bind($service, function () use ($entity) {
                    return $entity;
                });
            }

            $dashboards[$dashboard_alias] = $dashboard;
        }

        config(['dashboard.dashboards' => $dashboards]);

        /** Register dashboard routes */
        $mapper = new RoutesMapper($dashboards);
        $mapper->map();
    }

    public function register()
    {
        $this->app->singleton('dashboard', function () {
            return app(Dashboard::class);
        });

        $this->mergeConfigFrom(
            __DIR__ . '/config/dashboard.php', 'dashboard'
        );

        $this->commands([
            \SmallTeam\Dashboard\Commands\EntityMake::class,
        ]);
    }

}