# lann-route

[![PHP version](https://badge.fury.io/ph/calject%2Flann-route.svg)](https://badge.fury.io/ph/calject%2Flann-route)
[![license](https://img.shields.io/packagist/l/calject/lann-route)](https://github.com/calject/lann-route/blob/master/LICENSE)

## 版本说明

### v1.0

* 基础版本(通过注解(doc注释)自动解析路由文件)

### v1.1

* 添加`php artisan calject:route:file` 命令根据注解(doc注释)生成路由文件

```
Description:
  根据注解路由生成路由文件

Usage:
  calject:route:file [options]

Options:
      --path[=PATH]     设置扫描的路径参数(目录/具体路由文件)，默认为app/Http/Controllers
      --env[=ENV]       设置生成的env环境路由,仅生成参数内配置的env路由,多个环境以,分割(例: local,develop),默认不检查@env(...)
      --force           是否强制生成文件，将覆盖原有文件,默认已存在的文件不重复生成
```

* 路由生成示例(routes/test.php)

```php
<?php

/**
 * Class UploadController
 * @package App\Http\Controllers\Annotation
 */
Route::group(["prefix" => "file", "namespace" => "Annotation"], function () {
    // 文件上传
    Route::post('upload', 'UploadController@upload');
    // 文件下载
    Route::get('download', 'UploadController@download');
});

/**
 * Class TestController
 * @package App\Http\Controllers\Annotation
 */
Route::group(["prefix" => "annotation", "middleware" => ["api"], "namespace" => "Annotation"], function () {
    Route::get('getPath', 'TestController@getPath');
    Route::post('setPath', 'TestController@setPath');
    Route::match(["get", "post", "put", "delete"], 'user', 'TestController@user')->name('user');
    Route::match(["get", "post", "put", "delete"], 'file', 'TestController@file');
    Route::match(["get", "post", "put", "delete"], 'article', 'TestController@article');
});
```

## **Table of Contents**

* [一、介绍](#一介绍-top)
* [二、安装](#二安装-top)
* [三、使用](#三使用-top)
    * [Class Annotation Tag](#class-annotation-tag)
        * [@group](#group) route group
        * [@env](#env) 设置路由生效环境
        * [@prefix](#prefix) route prefix
        * [@middleware](#middleware) route middleware
        * [@any](#any) 设置当前控制器请求方式默认为any
        * [@restful](#restful) 设置当前控制器请求方式默认为restful(get|post|put|delete)
        * [@get](#get) 设置当前控制器请求方式默认为get
        * [@post](#post) 设置当前控制器请求方式默认为post
        * [@put](#put) 设置当前控制器请求方式默认为put
        * [@delete](#delete) 设置当前控制器请求方式默认为delete
    * [Function Annotation Tag](#function-annotation-tag)
        * [@uri](#uri) 设置请求方法的请求路径
        * [@name](#name) 设置路由别名
        * [@des](#des) 添加路由描述
        * [@env](#env-1) 设置当前请求方法生效环境
        * [@prefix](#prefix-1) route prefix
        * [@middleware](#middleware-1) route middleware
        * [@any](#any-1) 设置当前路由请求方式为any
        * [@restful](#restful-1) 设置当前路由请求方式为restful(get|post|put|delete)
        * [@get](#get-1) 设置当前路由请求方式为get
        * [@post](#post-1) 设置当前路由请求方式为post
        * [@put](#put-1) 设置当前路由请求方式为put
        * [@delete](#delete-1) 设置当前路由请求方式为delete
* [四、拓展](#expand)

## <span id="introduce">一、介绍</span> [top](#lann-route)

laravel annotation route(laravel 注解路由实现)

### 缓存

* 不使用该路由功能，仅使用根据注解生成路由文件(开发中... v1.1)
* 配合laravel框架内置路由缓存`php artisan route:cache`使用(仅在生成路由缓存时遍历一次控制器文件)

## <span id="install">二、安装</span> [top](#lann-route)

```
composer require calject/lann-route
```

## <span id="usage">三、使用</span> [top](#lann-route)

> `AnnotationRouteLocalProvider`、`AnnotationRouteProvider`、`AnnotationRoute`

#### 服务提供者注册实现
* `config/app.php` => 'providers' 属性中添加`AnnotationRouteLocalProvider`或`AnnotationRouteProvider`服务提供者

> `AnnotationRouteLocalProvider`仅在env环境为local中生效, `AnnotationRouteProvider` 在所有环境中生效, 可通过`AnnotationRoute`的`env`方法设置

#### 自定义实现
* `app/Providers/RouteServiceProvider.php` 中添加注解实现`AnnotationRoute`

```php
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapCreditRoutes();

        $this->mapDevelopRoutes();
        
        $this->mapTestRoutes();
        
        // 添加注解实现
        $annotationRoute = new AnnotationRoute();
        // $annotationRoute->envs('local');                 // 设置生效环境
        // $annotationRoute->envs(['local', 'develop']);    // 设置生效环境
        $annotationRoute->register(...);                    // 注册:实现Calject\LannRoute\Contracts\AnnotationTagInterface接口的tag类
        $annotationRoute->mapRefRoutes();
    
    }
```

* 示例        

```php
<?php

namespace App\Http\Controllers\Annotation;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class TestController
 * @package App\Http\Controllers\Annotation
 * @group(prefix='annotation', middleware='api')
 * @restful()
 */
class TestController extends Controller
{
    /**
     * @param Request $request
     * @return ResponseFactory|Response
     * @uri('testA')
     * @name(testA)
     */
    public function testA(Request $request)
    {
        return response('testA');
    }
    
    /**
     * @param Request $request
     * @return ResponseFactory|Response
     * @get('testB')
     */
    public function testB(Request $request)
    {
        return response('testB');
    }
    
    /**
     * @param Request $request
     * @return ResponseFactory|Response
     * @prefix('ttt')
     * @uri('testC, testC2')
     */
    public function testC(Request $request)
    {
        return response('testB');
    }
    
    /**
     * @param Request $request
     * @return ResponseFactory|Response
     * @api(uri='testD', method='post', middleware='test,td')
     */
    public function testD(Request $request)
    {
        return response('testB');
    }
    
    /**
     * @param Request $request
     * @return ResponseFactory|Response
     * @uri(testE)
     * @name(testE)
     * @middleware(query)
     */
    public function testE(Request $request)
    {
        return response('testD');
    }
}
```

* 执行`php artisan route:list`查看路由列表

```
+--------+--------------------------+-----------------------+------------+--------------------------------------------------------------+-------------+
| Domain | Method                   | URI                   | Name       | Action                                                       | Middleware  |
+--------+--------------------------+-----------------------+------------+--------------------------------------------------------------+-------------+
|        | GET|POST|PUT|DELETE|HEAD | annotation/testA      | testA      | App\Http\Controllers\Annotation\TestController@testA         | api         |
|        | GET|HEAD                 | annotation/testB      |            | App\Http\Controllers\Annotation\TestController@testB         | api         |
|        | POST                     | annotation/testD      |            | App\Http\Controllers\Annotation\TestController@testD         | api,test,td |
|        | GET|POST|PUT|DELETE|HEAD | annotation/testE      | testE      | App\Http\Controllers\Annotation\TestController@testE         | api,query   |
|        | GET|POST|PUT|DELETE|HEAD | ttt/annotation/testC  |            | App\Http\Controllers\Annotation\TestController@testC         | api         |
|        | GET|POST|PUT|DELETE|HEAD | ttt/annotation/testC2 |            | App\Http\Controllers\Annotation\TestController@testC         | api         |
+--------+--------------------------+-----------------------+------------+--------------------------------------------------------------+-------------+

```


## <span id="class-annotation-tag">Class Annotation Tag</span>

> 类注解(作用于所有类方法上,方法上定义同类型tag覆盖类定义)

#### <span id="c-group">`@group(...)`</span>
* `@group(xxx='xxx',xxx='xxx',...)`
    * `prefix`[string] : 前缀
    * `method`[array] : 请求类型, -- 
        * `get`
        * `post`
        * `put`
        * `delete`
        * `...`
    * `middleware`[array] : 请求中间件
* 示例1: `@group(method='get', prefix='query', middleware='api_query')`
* 示例2: `@group(method='get,post,put,delete', prefix='test')`

#### <span id="c-env">`@env(...)`</span>
* `@env('local')` 添加访问环境限制
* 示例1: @env('local')    local环境生效
* 示例2: @env(all)        所有环境生效
* 示例2: @env(local, develop, feature) [local,develop,feature]环境生效

#### <span id="c-prefix">`@prefix(...)`</span>
* `@prefix('string')` 添加请求前缀
* 示例: @prefix('test')

#### <span id="c-middleware">`@middleware(...)`</span>
* `@middleware('array')` 添加请求中间件
* 示例1: `@middleware('api')`
* 示例2: `@middleware('api, query')`

#### <span id="c-any">`@any(...)`</span>
* `@any()`  添加restful定义，等同于`@method('get,post,put,delete,...')`
* `@any(xxx='xxx',xxx='xxx',...)`
    * `prefix`[string] : 前缀
    * `middleware`[array] : 请求中间件
* 示例1: `@any()`
* 示例2: `@any(prefix='api', middleware='api')`

#### <span id="c-restful">`@restful(...)`</span>
* `@restful()`  添加restful定义，等同于`@method('get,post,put,delete')`
* `@restful(xxx='xxx',xxx='xxx',...)`
    * `prefix`[string] : 前缀
    * `middleware`[array] : 请求中间件
* 示例1: `@restful()`
* 示例2: `@restful(prefix='api', middleware='api')`

#### <span id="c-get">`@get(...)`</span>
* `@get()`
* `@get(xxx='xxx',xxx='xxx',...)`
    * `prefix`[string] : 前缀
    * `middleware`[array] : 请求中间件
* 示例1: `@get()`
* 示例2: `@get(prefix='api', middleware='api')`

#### <span id="c-post">`@post(...)`</span>
* `@post()`
* `@post(xxx='xxx',xxx='xxx',...)`
    * `prefix`[string] : 前缀
    * `middleware`[array] : 请求中间件
* 示例1: `@post()`
* 示例2: `@post(prefix='api', middleware='api')`

#### <span id="c-put">`@put(...)`</span>
* `@put()`
* `@put(xxx='xxx',xxx='xxx',...)`
    * `prefix`[string] : 前缀
    * `middleware`[array] : 请求中间件
* 示例1: `@put()`
* 示例2: `@put(prefix='api', middleware='api')`

#### <span id="c-delete">`@delete(...)`</span>
* `@delete()`
* `@delete(xxx='xxx',xxx='xxx',...)`
    * `prefix`[string] : 前缀
    * `middleware`[array] : 请求中间件
* 示例1: `@delete()`
* 示例2: `@delete(prefix='api', middleware='api')`


## <span id="function-annotation-tag">Function Annotation Tag</span>

#### <span id="f-uri">`@uri(...)`</span>
* `@uri('array')` 添加请求地址
* 示例1: @uri('test')
* 示例2: @uri('test1, test2') 将生成两个地址: xxx/test1 xxx/test2

#### <span id="f-name">`@name(...)`</span>
* `@name('string')` 添加别名
* 示例 @name('test')

#### <span id="c-des">`@des(...)`</span>
* `@des('路由描述')` 生成路由文件的路由描述
* 示例1: @env('请求测试')

#### <span id="c-env">`@env(...)`</span>
* `@env('local')` 添加访问环境限制
* 示例1: @env('local')    local环境生效
* 示例2: @env(all)        所有环境生效
* 示例2: @env(local, develop, feature) [local,develop,feature]环境生效

#### <span id="f-prefix">`@prefix(...)`</span>
* `@prefix('string')` 添加请求前缀
* 示例: @prefix('test')

#### <span id="f-middleware">`@middleware(...)`</span>
* `@middleware('array')` 添加请求中间件
* 示例1: `@middleware('api')`
* 示例2: `@middleware('api, query')`

#### <span id="c-any">`@any(...)`</span>
* `@any()`  添加restful定义，等同于`@method('get,post,put,delete,...')`
* `@any(xxx='xxx',xxx='xxx',...)`
    * `prefix`[string] : 前缀
    * `middleware`[array] : 请求中间件
* 示例1: `@any()`
* 示例2: `@any(prefix='api', middleware='api')`

#### <span id="f-restful">`@restful(...)`</span>
* `@restful()`      添加restful定义，等同于`@method('get,post,put,delete')`
* `@restful('uri[array]')` 添加restful定义，并添加uri访问地址
* `@restful(xxx='xxx',xxx='xxx',...)`
    * `prefix`[string] : 前缀
    * `middleware`[array] : 请求中间件
* 示例1: `@restful()`
* 示例2: `@restful(prefix='api', middleware='api')`
* 示例3: `@restful('test')`

#### <span id="f-get">`@get(...)`</span>
* `@get()`
* `@get('uri[array]')`
* `@get(xxx='xxx',xxx='xxx',...)`
    * `prefix`[string] : 前缀
    * `middleware`[array] : 请求中间件
* 示例1: `@get()`
* 示例2: `@get(prefix='api', middleware='api')`
* 示例3: `@egt('test')`

#### <span id="f-post">`@post(...)`</span>
* `@post()`
* `@post('uri[array]')`
* `@post(xxx='xxx',xxx='xxx',...)`
    * `prefix`[string] : 前缀
    * `middleware`[array] : 请求中间件
* 示例1: `@post()`
* 示例2: `@post(prefix='api', middleware='api')`
* 示例3: `@post('test')`

#### <span id="f-put">`@put(...)`</span>
* `@put()`
* `@put('uri[array]')`
* `@put(xxx='xxx',xxx='xxx',...)`
    * `prefix`[string] : 前缀
    * `middleware`[array] : 请求中间件
* 示例1: `@put()`
* 示例2: `@put(prefix='api', middleware='api')`
* 示例3: `@put('test')`

#### <span id="f-delete">`@delete(...)`</span>
* `@delete()`
* `@delete('uri[array]')`
* `@delete(xxx='xxx',xxx='xxx',...)`
    * `prefix`[string] : 前缀
    * `middleware`[array] : 请求中间件
* 示例1: `@delete()`
* 示例2: `@delete(prefix='api', middleware='api')`
* 示例3: `@delete('test')`


### <span id="expand">四、拓展</span> [top](#lann-route)

1. 实现`Calject\LannRoute\Contracts\AnnotationTagInterface`接口或者继承`Calject\LannRoute\Contracts\AbsAnnotationTag`抽象类, 参考`src/Components/Tag/`目录下的tag实现
2. 在`AnnotationRoute`中注册

* 示例
```php
<?php

namespace Calject\LannRoute\Components\Tag;

use Calject\LannRoute\Components\Model\RouteClassData;
use Calject\LannRoute\Components\Model\RouteFuncData;
use Calject\LannRoute\Constant\RouteConstant;
use Calject\LannRoute\Contracts\AbsAnnotationTag;

/**
 * Class Route
 * @package Calject\LannRoute\Components\Tag
 * @example @route(xxx='xxx',xxx='xxx',...)
 */
class Route extends AbsAnnotationTag
{
    /**
     * @return string
     */
    public function tag(): string
    {
        return 'route';
    }
    
    /**
     * @param RouteFuncData|RouteClassData $route
     * @param array|string $params
     * @return mixed
     */
    public function handle($route, $params)
    {
        $this->doRoutes($route, $params);
    }
    
    /**
     * tag过滤参数(数组或者为空)
     * @return array|mixed|null|void
     */
    public function tagParams()
    {
        return ['middleware', 'uri', 'method'];
    }
    
    /**
     * 作用范围, 可选: class, function
     * @return array|string
     */
    public function scope()
    {
        return RouteConstant::SCOPE_FUNCTION;
    }
}
```

* 注册

```php
// 添加注解实现
$annotationRoute = new AnnotationRoute();
$annotationRoute->register(new Tag());
$annotationRoute->mapRefRoutes();
```

* 使用

```php
<?php

namespace App\Http\Controllers\Annotation;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class TestController
 * @package App\Http\Controllers\Annotation
 * @group(prefix='annotation', middleware='api')
 */
class TestController extends Controller
{
    /**
     * @param Request $request
     * @return ResponseFactory|Response
     * @route(uri='test', method='get')
     */
    public function test(Request $request)
    {
        return response('test');
    }
}
```

## Plan

### v1.2 (待定...)

* 添加路由配置`route.ini`配置文件，作用于文件夹下所有控制器类
    * 可以使用已定义的所有作用于CLASS级别的`tag`，如`@restful`、`@file()`、`@middleware()`
    * 子级目录将覆盖父级目录的定义
    * 类中定义的`@tag(...)`将覆盖`route.ini`的配置
        * 如`route.ini`中定义`prefix=api`, class中添加`@prefix(test)`,最终class的`prefix`为`test/...`

##### 待定模式`@tag{}`、`@tag[]`

* 添加`@tag{...}`模式,标识`tag`相关内容为追加, 不覆盖`php.ini`及上级类的`@tag`
    * 支持`tag`
        * `@method`、`@middleware`、`@prefix`、`@file`、`@env`
    * 如`route.ini`中定义`prefix=api`, class中添加`@prefix{test}`,最终class的`prefix`为`api/test/...`

* 添加`@tag[...]`模式，标识`tag`相关内容为删除, 删除其上层相关配置
    * 如`route.ini`中定义`middleware=api,query`, class中添加`@middleware[api]`,最终class的`middleware`为`query`
    * 如`TestController`注释中添加`@method(get,post)`, function注释中添加`@method[post]`,最终function请求`method`为`get`

* 示例:
```
method=get,post,put,delete
prefix=annotation
middleware=api
...
```

### v1.1 (开发...)

* 添加`@env(...)`作用环境
    * 作用环境: CLASS 、FUNCTION
    * 示例: @env(produce) | @env(local,develop)

* 添加根据路由注解生成路由文件功能
    * 添加命令`php artisan calject:route:file` 根据路由注解生成文件
    * 添加tag`@file(...)` : 生成路由的文件名(作用于当前类)
        * 作用范围: CLASS
        * 示例: @file(route.php) | @file(test/route.php) 将在`routes`目录下生成对应的路由文件

* 示例

```php
<?php

/**
 * Class TestController
 * @env(produce,develop,local)  限定produce,develop,local环境生效
 * @file(route.php)
 */
class TestController extends Controller
{
    /**
     * @return ResponseFactory|Response
     * @route(uri='test', method='get')
     * @env(local) 限定local生效
     */
    public function test()
    {
        return response('test');
    }
}
```