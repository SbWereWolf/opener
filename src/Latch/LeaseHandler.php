<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 21:49
 */

namespace Latch;


use DataStorage\LeaseAccess;

class LeaseHandler
{
    private $leaseAccess = null;

    public function __construct(string $dataPath)
    {
        $this->leaseAccess = new LeaseAccess($dataPath);
    }

    public function getActual(): ILeaseSet
    {
        $result = $this->getLeaseAccess()->getActual()->getData();
        return $result;
    }

    /**
     * @return string
     */
    private function getLeaseAccess(): LeaseAccess
    {
        return $this->leaseAccess;
    }

    public function getCurrent(Lease $lease): ILeaseSet
    {
        $result = $this->getLeaseAccess()->getCurrent($lease)->getData();
        return $result;
    }
}
