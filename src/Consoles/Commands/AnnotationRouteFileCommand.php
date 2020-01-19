<?php
/**
 * Author: 沧澜
 * Date: 2019-12-31
 */

namespace Calject\LannRoute\Consoles\Commands;

use Calject\LannRoute\Components\Model\RouteFile;
use Calject\LannRoute\Components\RouteManager;
use Calject\LannRoute\Helper\RouteDataHelper;
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
        $isForce = true;
        $optPath = $this->getOption('path', app_path('Http/Controllers'));
        $optEnv = $this->getOption('env', app('env'));
        $routeManager = new RouteManager($optPath);
        array_map(function (RouteFile $routeFile) use ($optEnv, $routeManager, &$files) {
            $classRoute = $routeFile->getRouteClass();
            $methodRoutes = $routeFile->getRouteFunctions();
            $filePath = $classRoute->getFile();
            if (!RouteDataHelper::checkEnvs($optEnv, $classRoute) || !$filePath) {
                return;
            }
            $filePath = base_path('routes/' . ltrim(str_replace('.php', '', $filePath), '/') . '.php');
            $content = ($files[$filePath] ?? "<?php") . "\n\n";
            $content .= '// ' . $classRoute->getClass() . "\n";
            $funcContent = '';
            if ($isGroup = (bool)($group = $classRoute->toGroupArray())) {
                $content .= "Route::group(" . str_replace(',', ', ', $this->arrayToStr($group)) . ", function () {\n";
            }
            usort($methodRoutes, function ($item1, $item2) {
                return count($item1->getMethod()) > count($item2->getMethod());
            });
            foreach ($methodRoutes as $methodRoute) {
                if (count($methods = $methodRoute->getMethod()) > 1) {
                    $routeStr = 'Route::match(' . $this->arrayToStr($methods) . ', ';
                } elseif (isset($methods[0])) {
                    $routeStr = 'Route::' . $methods[0] . '(';
                } else {
                    continue;
                }
                foreach ($methodRoute->getUri() as $uri) {
                    $funcContent .= $isGroup ? str_repeat(' ', 4) : '';
                    $funcContent .= $routeStr . "'$uri', '" . str_replace($classRoute->getRealNamespace().'\\', '', $methodRoute->getAction()) . "')";
                    $funcContent .= ($methodRoute->getName() ? '->name(\'' . $methodRoute->getName() . '\')' : '') . ";\n";
                }
            }
            if ($funcContent) {
                $files[$filePath] = $content . $funcContent . ($isGroup ? '});' : '');
            }
            
        }, $routeManager->getRouteFiles());
        
        if ($files) {
            foreach ($files as $path => $content ) {
                if (!$isForce && file_exists($path)) {
                    echo "文件{$path}已存在.";
                } else {
                    $this->mkdir(dirname($path));
                    file_put_contents($path, $content);
                }
            }
        } else {
            echo "生成路由文件失败";
        }
    }
    
    /**
     * @param array $array
     * @return mixed
     */
    protected function arrayToStr(array $array)
    {
        return str_replace('}', ']',
            str_replace('{', '[',
                str_replace(':', ' => ', json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE))
            )
        );
    
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