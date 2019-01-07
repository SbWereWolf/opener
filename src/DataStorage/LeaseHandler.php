<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 21:49
 */

namespace DataStorage;


use Latch\Content;
use Latch\ILease;
use Latch\LeaseSet;

class LeaseHandler extends DataHandler
{
    private $leaseAccess = null;

    public function getActual(): Content
    {
        $result = $this->getLeaseAccess()->getActual()->getData();
        return $result;
    }

    /**
     * @return string
     */
    private function getLeaseAccess(): LeaseAccess
    {
        $leaseAccess = $this->leaseAccess;
        $isExists = !empty($leaseAccess);

        if (!$isExists) {
            $access = $this->getAccess();
            $leaseAccess = new LeaseAccess($access);
            $this->leaseAccess = $leaseAccess;
        }

        return $leaseAccess;
    }

    public function getCurrent(ILease $lease): Content
    {
        $result = $this->getLeaseAccess()->getCurrent($lease)->getData();
        return $result;
    }

    public function registerLease(ILease $lease): Content
    {
        $result = new LeaseSet();

        $this->begin();
        try {
            $this->getLeaseAccess()->isLeasePossible($lease);

            $isPossible = $this->getLeaseAccess()->getRowCount() > 0;

            if ($isPossible) {
                $result = $this->getLeaseAccess()->insert($lease)->getData();
            }

            $this->commit();
        } catch (\Exception $e) {
            $this->rollBack();
        }

        return $result;
    }

    public function overrideLease(ILease $lease): Content
    {
        $result = new LeaseSet();

        $this->begin();
        try {
            $this->getLeaseAccess()->isLeasePossible($lease);

            $isPossible = $this->getLeaseAccess()->getRowCount() > 0;

            if ($isPossible) {
                $this->getLeaseAccess()->update($lease)->getData();
            }

            $this->commit();
        } catch (\Exception $e) {
            $this->rollBack();
        }

        return $result;
    }
}
