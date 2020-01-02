<?php
/**
 * Author: 沧澜
 * Date: 2019-12-31
 */

namespace Calject\LannRoute\Providers;

use Calject\LannRoute\Consoles\Commands\AnnotationRouteFileCommand;
use Illuminate\Support\ServiceProvider;

/**
 * Class CommandsProvider
 * @package Calject\LaravelProductivity\Providers
 */
class CommandsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AnnotationRouteFileCommand::class,
            ]);
        }
    }
    
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    
    
    }
    
}