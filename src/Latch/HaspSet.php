<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 1:45
 */

namespace Latch;


class HaspSet extends DataSet
{
    public function push($element): bool
    {
        $result = false;

        $isValid = $element instanceof IHasp;
        if ($isValid) {
            $this->collection[] = $element;
            $result = true;
        }

        return $result;
    }
}
