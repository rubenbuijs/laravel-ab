<?php namespace Jenssegers\AB;

use Jenssegers\AB\Session\LaravelSession;
use Jenssegers\AB\Session\CookieSession;

use Illuminate\Support\ServiceProvider;

class TesterServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Config
        $this->publishes([
            __DIR__.'/path/to/config/ab.php' => config_path('ab.php'),
        ], 'config');
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ab', function($app) {
            return new Tester(new LaravelSession);
        });


        $this->registerCommands();
    }

    /**
     * Register Artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        // Available commands.
        $commands = ['install', 'flush', 'report', 'export'];

        // Bind the command objects.
        foreach ($commands as &$command)
        {
            $class = 'Jenssegers\\AB\\Commands\\' . ucfirst($command) . 'Command';
            $command = "ab::command.$class";

            $this->app->bind($command, function($app) use ($class)
            {
                return new $class();
            });
        }

        // Register artisan commands.
        $this->commands($commands);
    }

}
