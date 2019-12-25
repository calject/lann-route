<?php

namespace Calject\LannRoute\Providers;

use Calject\LannRoute\AnnotationRoute;
use Illuminate\Support\ServiceProvider;

class AnnotationRouteProvider extends ServiceProvider
{
    
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        (new AnnotationRoute())->mapRefRoutes();
    }
    
}
