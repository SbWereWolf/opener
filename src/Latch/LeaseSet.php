<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 1:45
 */

namespace Latch;


class LeaseSet implements ILeaseSet
{
    private $collection = array();
    private $status = false;

    public function push(ILease $element): bool
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
    public function setSuccessStatus(){
        $this->status = true;
    }

    public function setFailStatus(){
        $this->status = false;
    }
    public function isSuccess(){
        return $this->status == true;

    }
}
