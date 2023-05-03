<?php

namespace Aman5537jains\ReportBuilder;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
 

class ReportBuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
     
        
      
        $this->publishes([
            __DIR__.'/report_manager_config.php' => config_path('reportconfig.php','config'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        // $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'ReportBuilder');

        $this->publishes([
            __DIR__.'/resources/assets' => public_path('ReportBuilder'),
          ], 'assets');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        // $this->publishes([
        //     __DIR__.'/views' => base_path('resources/views/aman'),
        // ]);
    
    }
 
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/report_manager_config.php' ,'reportconfig'
        );
      
    }
 
}
