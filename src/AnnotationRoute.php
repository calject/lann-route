<?php
/**
 * Author: 沧澜
 * Date: 2019-12-11
 */

namespace Calject\LannRoute;

use Calject\LannRoute\Components\Model\RouteFile;
use Calject\LannRoute\Components\RouteManager;
use Calject\LannRoute\Contracts\AbsRouteData;
use Calject\LannRoute\Contracts\AnnotationTagInterface;
use Calject\LannRoute\Helper\RouteDataHelper;
use CalJect\Productivity\Components\DataProperty\CallDataProperty;
use CalJect\Productivity\Contracts\DataProperty\TCallDataPropertyByName;
use Illuminate\Support\Facades\Route;

/**
 * Class AnnotationRoute
 * @package Calject\LannRoute
 * ---------- set ----------
 * @method AnnotationRoute setEnvs($envs)                  生效环境
 * @method $this setControllers($controllers)    注解查询的路径[相对路径]
 *
 * ---------- get ----------
 * @method mixed getEnvs()           生效环境
 * @method mixed getControllers()    注解查询的路径[相对路径]
 *
 * ---------- apt ----------
 * @method $this|mixed envs($envs = null)                  生效环境
 * @method $this|mixed controllers($controllers = null)    注解查询的路径[相对路径]
 */
class AnnotationRoute extends CallDataProperty
{
    use TCallDataPropertyByName;
    /**
     * @var string
     */
    private $namespace = 'App\Http\Controllers';
    
    /**
     * 当前env环境配置
     * @var string
     */
    protected $appEnv = '';
    
    /**
     * @note 生效环境
     * @var mixed
     * @explain array|string 传入数组或者字符 默认为所有环境生效
     * @example local 、 produce 、 ['local', 'develop'] 、 ...
     */
    protected $envs = [];
    
    /**
     * @var RouteManager
     */
    protected $routeManager;
    
    
    /**
     * AnnotationRoute constructor.
     */
    public function _init()
    {
        $this->appEnv = app('env');
        $this->routeManager = new RouteManager(app_path('Http/Controllers'), $this->namespace);
    }
    
    /**
     * @param AnnotationTagInterface $annotationTag
     * @return $this
     */
    public function register(AnnotationTagInterface $annotationTag)
    {
        $this->routeManager->register($annotationTag);
        return $this;
    }
    
    /**
     * @param AnnotationTagInterface[] $tags
     * @return $this
     */
    public function registers(array $tags)
    {
        $this->routeManager->registers($tags);
        return $this;
    }
    
    /**
     * @return RouteManager
     */
    public function getRouteManager(): RouteManager
    {
        return $this->routeManager;
    }
    
    /**
     * @explain 遍历控制器文件
     */
    public function mapRefRoutes()
    {
        $this->registerRoutes();
    }
    
    /**
     * 注册路由
     */
    public function registerRoutes()
    {
        if ($this->envs && !in_array($this->appEnv, (array)$this->envs)) {
            return;
        }
        array_map(function (RouteFile $routeFile) {
            $classTagData = $routeFile->getRouteClass();
            $methodRoutes = $routeFile->getRouteFunctions();
            if (!RouteDataHelper::checkEnvs($this->appEnv,$classTagData)) {
                return;
            }
            $router = Route::namespace($this->namespace);
            if ($classParams = $classTagData->getOther()) {
                foreach ($classParams as $property => $values) {
                    $router->{$property}($values);
                }
            }
            $classTagData->getPrefix() && $router->prefix($classTagData->getPrefix());
            $classTagData->getMiddleware() && $router->middleware($classTagData->getMiddleware());
            $router->group(function () use ($methodRoutes) {
                array_map(function ($funcTag) {
                    if (RouteDataHelper::checkEnvs($this->appEnv, $funcTag)) {
                        foreach ($funcTag->getUri() as $uri) {
                            if ($funcTag->getMethod() && $uri ) {
                                $route = Route::match($funcTag->getMethod(), $uri, $funcTag->getAction());
                                $route->name($funcTag->getName());
                                $route->prefix($funcTag->getPrefix());
                                $route->middleware($funcTag->getMiddleware());
                                foreach ($funcTag->getOther() as $key => $value) {
                                    $route->{$key}($value);
                                }
                            }
                        }
                    }
                }, $methodRoutes);
            });
        }, $this->routeManager->getRouteFiles());
    }
    
}