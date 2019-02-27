<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 21:49
 */

namespace DataStorage\Lease;


use BusinessLogic\Basis\Content;
use BusinessLogic\Lease\ILease;
use BusinessLogic\Lease\LeaseSet;
use DataStorage\Basis\DataHandler;

class LeaseHandler extends DataHandler
{
    private $leaseAccess = null;

    public function getActual(): Content
    {
        $result = $this->getLeaseAccess()->getActual()->getData();

        return $result;
    }

    /**
     * @return LeaseAccessSqlight
     */
    private function getLeaseAccess(): LeaseAccessSqlight
    {
        $leaseAccess = $this->leaseAccess;
        $isExists = !empty($leaseAccess);

        if (!$isExists) {
            $access = $this->getAccess();
            $leaseAccess = new LeaseAccessSqlight($access);
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
            $isPossible = $this->isLeasePossible($lease);

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
            $isPossible = $this->isLeasePossible($lease);

            if ($isPossible) {
                $result  = $this->getLeaseAccess()->update($lease)->getData();
            }

            $this->commit();
        } catch (\Exception $e) {
            $this->rollBack();
        }

        return $result;
    }

    /**
     * @param ILease $lease
     * @return bool
     */
    private function isLeasePossible(ILease $lease): bool
    {
        $this->getLeaseAccess()->findFreeHours($lease);

        $isPossible = $this->getLeaseAccess()->getRowCount() > 0;

        return $isPossible;
    }
}
