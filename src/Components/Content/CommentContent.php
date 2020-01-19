<?php
/**
 * Author: 沧澜
 * Date: 2020-01-19
 */

namespace Calject\LannRoute\Content\Components;

use Calject\LannRoute\Contracts\AbsContent;

/**
 * Class Comment
 * @package Calject\LannRoute\Components
 */
class CommentContent extends AbsContent
{
    /**
     * @param array $keyValues
     * @param string $indent
     * @param int $indentLev
     * @param string $head
     * @return static
     */
    public static function make(array $keyValues, $indent = '', $indentLev = 1, $head = '')
    {
        $content = new static($indent, $indentLev, $head);
        foreach ($keyValues as $key => $value) {
            $content->append($key . ' ' . $value);
        }
        return $content;
    }
    
    /**
     * @param string $indent
     * @param int $indentLev
     * @param string $head
     */
    protected function init($indent = '', $indentLev = 1, $head = '')
    {
        $this->setContentHead($this->indentText("/**\n"));
        $this->setContentTail($this->indentText(" */\n"));
    }
    
    /**
     * @param array|string $content
     * @param int $indentLev
     * @param bool $next
     * @return $this
     */
    public function append($content, int $indentLev = 0, bool $next = true)
    {
        return parent::append((is_string($content) ? ' * ' : '') . $content, $indentLev, $next);
    }
    
}