<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 1:45
 */

namespace Latch;


class UserSet implements Content
{
    private $collection = array();
    private $status = false;

    public function push($element): bool
    {
        $result = false;

        $isValid = $element instanceof IUser;
        if ($isValid) {
            $this->collection[] = $element;
            $result = true;
        }

        return $result;
    }

    public function next()
    {
        foreach ($this->collection as $element) {
            yield $element;
        }
        return;
    }

    public function setSuccessStatus()
    {
        $this->status = true;
    }

    public function setFailStatus()
    {
        $this->status = false;
    }

    public function isSuccess(): bool
    {
        return $this->status == true;

    }
}
