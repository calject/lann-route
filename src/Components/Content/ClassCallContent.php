<?php
/**
 * Author: 沧澜
 * Date: 2020-01-19
 */

namespace Calject\LannRoute\Components\Content;

use Calject\LannRoute\Contracts\AbsContent;
use Calject\LannRoute\Utils\StrUtil;

/**
 * 类调用内容
 * Class Content
 * @package Calject\LannRoute\Components\Content
 */
class ClassCallContent extends AbsContent
{
    /**
     * @var string
     */
    protected $class;
    
    /**
     * @param string $class
     * @param string $indent
     * @param int $indentLev
     * @param string $head
     * @return static
     */
    public static function make(string $class, $indent = '', $indentLev = 1, $head = '')
    {
        $content = new static($indent, $indentLev, $head);
        $content->setIndentContent($class);
        return $content;
    }
    
    /**
     * @param string $indent
     * @param int $indentLev
     * @param string $head
     */
    protected function init($indent = '', $indentLev = 1, $head = '')
    {
        $this->setContentTail(';');
    }
    
    /**
     * @param string $func
     * @param array $params
     * @return $this
     */
    public function staticFunc(string $func, array $params = [])
    {
        return $this->appendClassFunc($func, $params, '::');
    }
    
    /**
     * @param string $func
     * @param array $params
     * @return ClassCallContent
     */
    public function func(string $func, array $params = [])
    {
        return $this->appendClassFunc($func, $params, '->');
    }
    
    /**
     * @param string $func
     * @param array $params
     * @param string $head
     * @return $this
     */
    protected function appendClassFunc(string $func, array $params = [], $head = '->')
    {
        array_walk($params, function (&$param) {
            $param = is_array($param) ? StrUtil::arrayToStr($param) : "'$param'";
        });
        return $this->appendContent([$head . $func . '(', implode(', ', $params), ')']);
    }
    
    
}