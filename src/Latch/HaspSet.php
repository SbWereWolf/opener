<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 1:45
 */

namespace Latch;


class HaspSet implements IHaspSet
{
    private $collection = array();

    public function push(IHasp $element): bool
    {
        $this->collection[] = $element;
        return true;

    }

    public function next()
    {
        foreach ($this->collection as $element) {
            yield $element;
        }
        return;
    }
}
