<?php
/**
 * Author: 沧澜
 * Date: 2019-12-11
 */

namespace Calject\LannRoute\Providers;

use Calject\LannRoute\AnnotationRoute;
use Illuminate\Support\ServiceProvider;

/**
 * Class AnnotationRouteLocalProvider
 * @package Calject\LannRoute\Providers
 */
class AnnotationRouteLocalProvider extends ServiceProvider
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
        (new AnnotationRoute())->envs('local')->mapRefRoutes();
    }
}