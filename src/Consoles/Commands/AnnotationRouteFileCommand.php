<?php
/**
 * Author: 沧澜
 * Date: 2019-12-31
 */

namespace Calject\LannRoute\Consoles\Commands;

use Calject\LannRoute\Components\Content\ClassCallContent;
use Calject\LannRoute\Components\Content\PhpFileContent;
use Calject\LannRoute\Components\Model\RouteFile;
use Calject\LannRoute\Components\RouteManager;
use Calject\LannRoute\Content\Components\CommentContent;
use Calject\LannRoute\Helper\RouteDataHelper;
use Calject\LannRoute\Utils\StrUtil;
use Illuminate\Console\Command;

/**
 * Class AnnotationRouteFileCommand
 * @package Calject\LannRoute\Consoles\Commands
 * * ---------- 获取参数及配置信息 ----------
 * @method string|array|null argument(string $key = null)           获取命令参数 {user*}(多个参数)、{user=foo}(带默认参数)、{user}、{user?}
 * @method array arguments()                                        获取命令参数列表
 *
 * @method string|array|bool|null option(string $key = null)        获取命令配置{--path}、{--path=}、{--path=*}(多个参数)
 * @method array options()                                          获取命令配置列表
 *
 */
class AnnotationRouteFileCommand extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calject:route:file
        {--path= : 设置扫描的路径参数(目录/具体路由文件)，默认为app/Http/Controllers}
        {--env= : 设置生成的env环境路由,仅生成参数内配置的env路由,多个环境以,分割(例: local,develop),默认不检查@env(...)}
        {--force : 是否强制生成文件，将覆盖原有文件,默认已存在的文件不重复生成}
    ';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '根据注解路由生成路由文件';
    
    /**
     * handle
     */
    public function handle()
    {
        $isForce = $this->getOption('force');
        $optPath = $this->getOption('path', app_path('Http/Controllers'));
        $optEnv = $this->getOption('env', app('env'));
        $routeManager = new RouteManager($optPath);
        $space = str_repeat(' ', 4);
        array_map(function (RouteFile $routeFile) use ($optEnv, $routeManager, &$files, $space) {
            $classRoute = $routeFile->getRouteClass();
            $methodRoutes = $routeFile->getRouteFunctions();
            $filePath = $classRoute->getFile();
            if (!RouteDataHelper::checkEnvs($optEnv, $classRoute) || !$filePath) {
                return;
            }
            $filePath = base_path('routes/' . ltrim(str_replace('.php', '', $filePath), '/') . '.php');
            $fileContent = PhpFileContent::make($files[$filePath] ?? '');
            $fileContent->append(CommentContent::make([
                'Class' => StrUtil::rightTo($classRoute->getClass(), '\\'),
                '@package' => StrUtil::leftTo($classRoute->getClass(), '\\')
            ]), 0, false);
            
            if ($isGroup = (bool)($group = $classRoute->toGroupArray())) {
                $fileContent->append("Route::group(" . StrUtil::arrayToStr($group) . ", function () {");
            }
            usort($methodRoutes, function ($item1, $item2) {
                return count($item1->getMethod()) > count($item2->getMethod());
            });
            foreach ($methodRoutes as $methodRoute) {
                if (count($methods = $methodRoute->getMethod()) > 1) {
                    $method = 'match';
                    $params = [$methods];
                } elseif (isset($methods[0])) {
                    $method = $methods[0];
                    $params = [];
                } else {
                    continue;
                }
                foreach ($methodRoute->getUri() as $uri) {
                    $route = ClassCallContent::make('Route', $space, (int)$isGroup);
                    if ($des = $methodRoute->getDes()) {
                        $route->setIndentContentHeadNext('//' . str_replace("\n", "\n" . $space, $des));
                    }
                    $route->staticFunc($method, array_merge_recursive(
                        $params,
                        [$uri, str_replace($classRoute->getRealNamespace().'\\', '', $methodRoute->getAction())]
                    ));
                    $methodRoute->getName() && $route->func('name', [$methodRoute->getName()]);
                    $fileContent->append($route);
                    unset($route);
                }
            }
            $isGroup && $fileContent->append('});');
            $files[$filePath] = $fileContent->toContent();
            unset($fileContent);
            
        }, $routeManager->getRouteFiles());
        
        if ($files) {
            foreach ($files as $path => $content ) {
                $realpath = str_replace(base_path('routes') . '/', '', $path);
                if (!$isForce && file_exists($path)) {
                    $this->line("生成路由文件[n]: $realpath 文件已存在, 使用--force参数强制覆盖.");
                } else {
                    $this->mkdir(dirname($path));
                    $this->info('生成路由文件[y]: ' . $realpath);
                    file_put_contents($path, $content);
                }
            }
        } else {
            echo "生成路由文件失败";
        }
    }
    
    /**
     * 递归创建目录
     * @param string $dir
     * @return bool
     */
    protected function mkdir($dir)
    {
        return is_dir($dir) || $this->mkdir(dirname($dir)) && mkdir($dir, 0755);
    }
    
    /**
     * @param string $key
     * @param null $default
     * @return array|bool|string|null
     */
    protected function getOption(string $key, $default = null)
    {
        return $this->option($key) ?? $default;
    }
    
    
    
}