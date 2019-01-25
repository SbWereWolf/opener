<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 1:45
 */

namespace BusinessLogic\Lease;


use BusinessLogic\Basis\DataSet;

class LeaseSet extends DataSet
{
    /**
     * @param $element
     * @return bool
     */
    public function push($element): bool
    {
        $result = false;

        $isValid = $element instanceof ILease;
        if ($isValid) {
            $this->collection[] = $element;
            $result = true;
        }

        return $result;
    }
}
