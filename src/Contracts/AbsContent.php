<?php
/**
 * Author: 沧澜
 * Date: 2020-01-19
 */

namespace Calject\LannRoute\Contracts;

/**
 * Class AbsContent
 * @package Calject\LannRoute\Contracts
 */
abstract class AbsContent implements IContent
{
    /**
     * 缩进
     * @var string
     */
    protected $indent = '';
    
    /**
     * 缩进等级
     * @var int
     */
    protected $indentLev = 1;
    
    /**
     * 单行固定头部内容
     * @var string
     */
    protected $head = '';
    
    /**
     * 文本内容
     * @var string
     */
    protected $content = '';
    
    /**
     * content头部内容
     * @var string
     */
    protected $contentHead = '';
    
    /**
     * content尾部内容
     * @var string
     */
    protected $contentTail = '';
    
    /**
     * AbsContent constructor.
     * @param string $indent
     * @param int $indentLev
     * @param string $head
     */
    public function __construct($indent = '', $indentLev = 1, $head = '')
    {
        $this->indent = $indent;
        $this->indentLev = $indentLev;
        $this->head = $head;
        $this->init($indent, $indentLev, $head);
    }
    
    /**
     * @param string $indent
     * @param int $indentLev
     * @param string $head
     */
    protected function init($indent = '', $indentLev = 1, $head = '')
    {
    
    }
    
    /*---------------------------------------------- function ----------------------------------------------*/
    
    /**
     * @param array|string $content 内容
     * @param int $indentLev 额外缩进等级 $this->indentLev + $indentLev
     * @param bool $next     是否换行
     * @return $this
     */
    public function append($content, int $indentLev = 0, bool $next = true)
    {
        if (is_string($content)) {
            $this->appendIndentContent($this->head . $content, $indentLev);
            $next && $this->nextLine();
        } elseif (is_array($content)) {
            foreach ($content as $item) {
                $this->append($item, $indentLev, $next);
            }
        } elseif ($content instanceof IContent) {
            $this->append($content->toContent(), $indentLev, $next);
        }
        return $this;
    }
    
    /**
     * @param string|array $content
     * @return $this
     */
    public function appendContent($content)
    {
        if (is_string($content)) {
            $this->content .= $content;
        } elseif (is_array($content)) {
            foreach ($content as $text) {
                $this->appendContent($text);
            }
        } elseif ($content instanceof IContent) {
            $this->appendContent($content->toContent());
        }
        return $this;
    }
    
    /**
     * @param string|array $content
     * @return $this
     */
    public function appendContentNext($content)
    {
        return $this->appendContent($content)->nextLine();
    }
    
    /**
     * @param string|array $content
     * @param $indentLev
     * @return $this
     */
    public function appendIndentContent($content, int $indentLev = 0)
    {
        return $this->appendContent($this->indent($indentLev) . $content);
    }
    
    /**
     * @param string|array $content
     * @param $indentLev
     * @return $this
     */
    public function appendIndentContentNext($content, $indentLev = 0)
    {
        return $this->appendIndentContent($content, $indentLev)->nextLine();
    }
    
    /**
     * @return $this
     */
    public function nextLine()
    {
        return $this->appendContent("\n");
    }
    
    /*---------------------------------------------- protected function ----------------------------------------------*/
    
    /**
     * @param int $indentLev
     * @return string
     */
    protected function indent($indentLev = 0)
    {
        return str_repeat($this->indent, $this->indentLev + $indentLev);
    }
    
    /**
     * @param string $str
     * @param int $indentLev
     * @return string
     */
    protected function indentText(string $str, $indentLev = 0)
    {
        return $this->indent($indentLev) . $str;
    }
    
    /*---------------------------------------------- set ----------------------------------------------*/
    
    
    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }
    
    /**
     * @param string $content
     * @return $this
     */
    public function setIndentContent(string $content)
    {
        return $this->setContent($this->indentText($content));
    }
    
    /**
     * @param string $indent
     * @return $this
     */
    public function setIndent(string $indent)
    {
        $this->indent = $indent;
        return $this;
    }
    
    /**
     * @param int $indentLev
     * @return $this
     */
    public function setIndentLev(int $indentLev)
    {
        $this->indentLev = $indentLev;
        return $this;
    }
    
    /**
     * @param string $head
     * @return $this
     */
    public function setHead(string $head)
    {
        $this->head = $head;
        return $this;
    }
    
    /**
     * @param string $contentHead
     * @return $this
     */
    public function setContentHead(string $contentHead)
    {
        $this->contentHead = $contentHead;
        return $this;
    }
    
    /**
     * @param string $contentHead
     * @return $this
     */
    public function setContentHeadNext(string $contentHead)
    {
        return $this->setContentHead($contentHead . "\n");
    }
    
    /**
     * @param string $contentHead
     * @return $this
     */
    public function setIndentContentHead(string $contentHead)
    {
        return $this->setContentHead($this->indentText($contentHead));
    }
    
    /**
     * @param string $contentHead
     * @return $this
     */
    public function setIndentContentHeadNext(string $contentHead)
    {
        return $this->setIndentContentHead($contentHead . "\n");
    }
    
    /**
     * @param string $contentTail
     * @return $this
     */
    public function setContentTail(string $contentTail)
    {
        $this->contentTail = $contentTail;
        return $this;
    }
    
    /*---------------------------------------------- get ----------------------------------------------*/
    
    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->contentHead . $this->content . $this->contentTail;
    }
    
    /**
     * 生成文本内容
     * @return string|array
     */
    public function toContent()
    {
        return $this->getContent();
    }
    
}