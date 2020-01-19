<?php
/**
 * Author: 沧澜
 * Date: 2020-01-19
 */

namespace Calject\LannRoute\Components\Content;

use Calject\LannRoute\Contracts\AbsContent;
use Calject\LannRoute\Utils\StrUtil;

/**
 * 匿名函数内容
 * Class ClosureContent
 * @package Calject\LannRoute\Components\Content
 */
class ClosureContent extends AbsContent
{
    /**
     * @var array
     */
    protected $params = [];
    
    /**
     * @var array
     */
    protected $uses = [];
    
    /**
     * @param array $params
     * @param array $uses
     * @param string $indent
     * @param int $indentLev
     * @param string $head
     * @return static
     */
    public static function make(array $params, array $uses = [], $indent = '', $indentLev = 1, $head = '')
    {
        $content = new static($indent, $indentLev, $head);
        $content->params = $params;
        $content->uses = $uses;
        return $content;
    }
    
    /**
     * @param string $indent
     * @param int $indentLev
     * @param string $head
     */
    protected function init($indent = '', $indentLev = 1, $head = '')
    {
        array_walk($this->params, function ($param) {
            return is_array($param) ? StrUtil::arrayToStr($param) : "'$param'";
        });
        $this->appendContent(['function (', implode(', ', $this->params), ')']);
        if ($this->uses) {
            $this->appendContent([' use (' . implode(', ', $this->uses) , ')']);
        }
        $this->appendContent(' {');
    }
    
    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }
    
    /**
     * @param array $uses
     * @return $this
     */
    public function setUses(array $uses)
    {
        $this->uses = $uses;
        return $this;
    }
    
}