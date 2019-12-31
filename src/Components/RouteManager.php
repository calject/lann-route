<?php
/**
 * Author: 沧澜
 * Date: 2019-12-31
 */

namespace Calject\LannRoute\Components;

use Calject\LannRoute\Components\Model\RouteClassData;
use Calject\LannRoute\Components\Model\RouteFile;
use Calject\LannRoute\Components\Model\RouteFuncData;
use Calject\LannRoute\Constant\RouteConstant;
use Calject\LannRoute\Contracts\AnnotationTagInterface;
use Calject\LannRoute\Utils\FileUtil;
use CalJect\Productivity\Utils\GeneratorFileLoad;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;

/**
 * Class RouteManager
 * @package Calject\LannRoute\Components
 */
class RouteManager
{
    /**
     * @var string
     */
    protected $namespace = 'App\Http\Controllers\Controller';
    
    /**
     * 路由文件路径(目录/文件)
     * @var string
     */
    protected $path;
    
    /**
     * @var RouteFile[]
     */
    protected $routeFiles = [];
    
    /**
     * @var RouteRegister
     */
    protected $routeRegister;
    
    /**
     * RouteManager constructor.
     * @param string $path
     * @param string|null $namespace
     */
    public function __construct(string $path, string $namespace = null)
    {
        if (!(is_dir($path) || is_file($path))) {
            throw new RuntimeException($path . ' must be a directory or file.');
        }
        $this->path = $path;
        $namespace && $this->namespace = $namespace;
        $this->initRouteRegister();
    }
    
    /**
     * init route register
     */
    protected function initRouteRegister()
    {
        $this->routeRegister = new RouteRegister();
        (new GeneratorFileLoad(dirname(__DIR__) . '/Components/Tag'))->eachFiles(function ($tagFile) {
            $className = 'Calject\LannRoute\Components\Tag\\' . str_replace('.php', '', basename($tagFile));
            if (!class_exists($className) || !is_subclass_of($className, 'Calject\LannRoute\Contracts\AnnotationTagInterface')) {
                return;
            }
            $this->routeRegister->register(new $className);
        });
    }
    
    /**
     * @return RouteFile[]
     */
    public function getRouteFiles()
    {
        if ($this->routeFiles) {
            return $this->routeFiles;
        } else {
            array_map(function ($filePath) {
                $className = str_replace('.php', '', str_replace('/', '\\', str_replace($this->path, $this->namespace, $filePath)));
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
                $methodRoutes && array_push($this->routeFiles, new RouteFile($classTagData, $methodRoutes));
            }, FileUtil::readFilesInDir($this->path));
            return $this->routeFiles;
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
     * @return $this
     */
    public function registers(array $tags)
    {
        $this->routeRegister->registers($tags);
        return $this;
    }
    
    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }
    
    /*---------------------------------------------- protected function ----------------------------------------------*/
    
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