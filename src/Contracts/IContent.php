<?php
/**
 * Author: 沧澜
 * Date: 2020-01-19
 */

namespace Calject\LannRoute\Contracts;

/**
 * Interface IContent
 * @package Calject\LannRoute\Contracts
 */
interface IContent
{
    /**
     * 生成文本内容
     * @return string|array
     */
    public function toContent();
}