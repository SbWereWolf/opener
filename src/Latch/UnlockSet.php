<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 16.01.2019 5:15
 */

namespace Latch;


class UnlockSet extends DataSet
{
    /**
     * @param $element
     * @return bool
     */
    public function push($element): bool
    {
        $result = false;

        $isValid = $element instanceof IUnlock;
        if ($isValid) {
            $this->collection[] = $element;
            $result = true;
        }

        return $result;
    }
}
