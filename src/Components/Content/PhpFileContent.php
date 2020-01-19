<?php
/**
 * Author: 沧澜
 * Date: 2020-01-19
 */

namespace Calject\LannRoute\Components\Content;

use Calject\LannRoute\Contracts\AbsContent;

/**
 * Class PhpFileContent
 * @package Calject\LannRoute\Components\Content
 */
class PhpFileContent extends AbsContent
{
    /**
     * @param string $content
     * @param string $indent
     * @param int $indentLev
     * @param string $head
     * @return static
     */
    public static function make(string $content, $indent = '', $indentLev = 1, $head = '')
    {
        $fileContent = new static($indent, $indentLev, $head);
        if ($content) {
            $fileContent->setContent($content . "\n");
        } else {
            $fileContent->setContentHead("<?php\n\n");
        }
        return $fileContent;
    }
    
}