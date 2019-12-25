<?php
/**
 * Author: 沧澜
 * Date: 2019-12-17
 */

namespace Calject\LannRoute\Components\Items;

use Calject\LannRoute\Contracts\AbsItem;

/**
 * Class ArrayItem
 * @package Calject\LannRoute\Components\Items
 */
class ArrayItem extends AbsItem
{
    /**
     * @param array|string $data
     * @return static
     */
    public function add($data)
    {
        $this->data = array_merge_recursive($this->data, (array)$data);
        return $this;
    }
}