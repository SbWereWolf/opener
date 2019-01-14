<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 14.01.2019 3:56
 */

namespace Latch;


class DataSet implements Content
{

    protected $collection = array();
    private $status = false;

    /**
     * @param $element
     * @return bool
     * @throws \Exception
     */
    public function push($element): bool
    {
        throw new \Exception('Method push($element) Not Implemented');
    }

    /**
     * @throws \Exception
     */
    public function next()
    {
        throw new \Exception('Method next() Not Implemented');
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
