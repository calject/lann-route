<?php
/**
 * Author: 沧澜
 * Date: 2019-12-11
 */

namespace Calject\LannRoute;

use Calject\LannRoute\Components\Model\RouteClassData;
use Calject\LannRoute\Components\Model\RouteFuncData;
use Calject\LannRoute\Components\RouteRegister;
use Calject\LannRoute\Constant\RouteConstant;
use Calject\LannRoute\Contracts\AnnotationTagInterface;
use CalJect\Productivity\Components\DataProperty\CallDataProperty;
use CalJect\Productivity\Contracts\DataProperty\TCallDataPropertyByName;
use CalJect\Productivity\Utils\GeneratorFileLoad;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionMethod;

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
     * @var RouteRegister
     */
    protected $routeRegister;
    
    /**
     * @note 生效环境
     * @var mixed
     * @explain array|string 传入数组或者字符 默认为所有环境生效
     * @example local 、 produce 、 ['local', 'develop'] 、 ...
     */
    protected $envs;
    
    /**
     * @note 注解查询的路径(相对路径)
     * @var mixed
     * @explain array|string 传入数组或者字符 默认为空查询app/Http/Controllers下所有控制器文件
     * @example Test 、 User 、['Test'、 'User'] 、 ...
     */
    protected $controllers;
    
    /**
     * init
     */
    protected function _init()
    {
        $this->routeRegister = new RouteRegister();
        (new GeneratorFileLoad(__DIR__ . '/Components/Tag'))->eachFiles(function ($tagFile) {
            $className = 'Calject\LannRoute\Components\Tag\\' . str_replace('.php', '', basename($tagFile));
            if (!class_exists($className) || !is_subclass_of($className, 'Calject\LannRoute\Contracts\AnnotationTagInterface')) {
                return;
            }
            $this->routeRegister->register(new $className);
        });
    }
    
    /**
     * @explain 遍历控制器文件
     */
    public function mapRefRoutes()
    {
        if ($this->envs && !in_array(app('env'), (array)$this->envs)) {
            return;
        }
        if ($this->controllers) {
            array_map(function ($path) {
                $this->registerRoutes($path);
            }, (array)$this->controllers);
        } else {
            $this->registerRoutes(app_path('Http/Controllers'));
        }
    }
    
    /**
     * @param AnnotationTagInterface $annotationTag
     * @return $this
     */
    public function register(AnnotationTagInterface $annotationTag)
    {
        $this->routeRegister->register($annotationTag);
        return $this;
    }
    
    /**
     * @param AnnotationTagInterface[] $tags
     * @return AnnotationRoute
     */
    public function registers(array $tags)
    {
        $this->routeRegister->registers($tags);
        return $this;
    }
    
    /*---------------------------------------------- protected ----------------------------------------------*/
    
    /**
     * @param $controllerPath
     */
    protected function registerRoutes($controllerPath)
    {
        (new GeneratorFileLoad($controllerPath))->eachFiles(function ($filePath) use ($controllerPath) {
            $className = str_replace('.php', '', str_replace('/', '\\', str_replace($controllerPath, $this->namespace, $filePath)));
            if (!class_exists($className) || !is_subclass_of($className, 'App\Http\Controllers\Controller')) {
                return;
            }
            $classTagData = new RouteClassData();
            /* ======== 解析路由注解 ======== */
            $refClass = new ReflectionClass($className);
            /* ======== 处理class tags ======== */
            $this->tagHandle($refClass->getDocComment(), RouteConstant::SCOPE_CLASS, $classTagData);
            array_map(function (ReflectionMethod $refMethod) use (&$methodRoutes, $className, $classTagData) {
                if ($refMethod->isPublic() && $refMethod->class == $className) {
                    $funcTagData = new RouteFuncData();
                    $funcTagData->method()->set($classTagData->getMethod());
                    $funcTagData->action(ltrim(str_replace($this->namespace, '', $className) . '@' . $refMethod->getName(), '\\'));
                    $methodRoutes[] = $this->tagHandle($refMethod->getDocComment(), RouteConstant::SCOPE_FUNCTION, $funcTagData);
                }
            }, $refClass->getMethods());
            /* ======== 注册路由 ======== */
            if ($methodRoutes) {
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
                        foreach ($funcTag->getUri() as $uri) {
                            if ($funcTag->getMethods() && $uri ) {
                                $route = Route::match($funcTag->getMethods(), $uri, $funcTag->getAction());
                                $route->name($funcTag->getName());
                                $route->prefix($funcTag->getPrefix());
                                $route->middleware($funcTag->getMiddleware());
                                foreach ($funcTag->getOther() as $key => $value) {
                                    $route->{$key}($value);
                                }
                            }
                        }
                    }, $methodRoutes);
                });
            }
        });
    }
    
    /**
     * @param string $docComment
     * @param string $scope
     * @param RouteFuncData|RouteClassData $tagData
     * @return RouteClassData|RouteFuncData
     */
    protected function tagHandle(string $docComment, string $scope, $tagData)
    {
        if ($classTags = $this->matchTags($docComment)) {
            foreach ($classTags as $tag => $content) {
                if ($tagObj = $this->routeRegister->get($tag, $scope)) {
                    $tagObj->handle($tagData, $content);
                }
            }
        }
        return $tagData;
    }
    
    /**
     * 获取所有tag(@key(xxx), @key(method='xxx', prefix='xxx'), ...)
     * @param string $docComment
     * @param null $default
     * @return array|null
     */
    protected function matchTags(string $docComment, $default = null)
    {
        if (preg_match_all("/\*?[ ]*@(\w+)\(['\"]?([^()]*?)['\"]?\)\n/s", $docComment, $matchs) && isset($matchs[2])) {
            foreach ($matchs[2] as $index => $content) {
                $tags[$matchs[1][$index]] = $this->toKeyValues($content);
            }
            return $tags ?? $default;
        } else {
            return $default;
        }
    }
    
    /**
     * 将字符串(key='values')转换为键值对
     * @param string $docComment
     * @return array|mixed
     * @example a='a', b='b' ==> ['a' => 'a', 'b' => 'b']
     */
    protected function toKeyValues(string $docComment)
    {
        if (preg_match_all("/(\w*)=['\"]*([^'\"()]*)['\"]*/", $docComment, $matchs) && $matchs[1]) {
            return array_combine($matchs[1], $matchs[2]);
        } else {
            return $docComment;
        }
    }
    
}