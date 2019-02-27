<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 16.01.2019 5:23
 */

namespace DataStorage\Unlock;


use BusinessLogic\Basis\Content;
use BusinessLogic\Basis\DataSet;
use BusinessLogic\Lease\ILease;
use BusinessLogic\Lease\Lease;
use BusinessLogic\Unlock\IUnlock;
use DataStorage\Basis\DataHandler;
use DataStorage\Lease\LeaseAccessSqlight;

class UnlockHandler extends DataHandler
{
    private $unlockAccess = null;
    private $leaseAccess = null;

    public function read(IUnlock $unlock): Content
    {
        $unlockAccess = $this->getUnlockAccess()->selectByPoint($unlock);
        $isSuccess = $unlockAccess->getRowCount() > 0 && $unlockAccess->isSuccess();

        $result = $unlockAccess->getData();
        if ($isSuccess) {
            $result->setSuccessStatus();
        }
        if (!$isSuccess) {
            $result->setFailStatus();
        }

        return $result;
    }

    /**
     * @return UnlockAccess
     */
    private function getUnlockAccess(): UnlockAccess
    {
        $unlockAccess = $this->unlockAccess;
        $isExists = !empty($unlockAccess);

        if (!$isExists) {
            $access = $this->getAccess();
            $unlockAccess = new UnlockAccess($access);
            $this->unlockAccess = $unlockAccess;
        }

        return $unlockAccess;
    }

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
    public function delete(IUnlock $unlock): Content
    {
        $result = $this->getUnlockAccess()->delete($unlock)->getData();

        return $result;
    }

    public function create(IUnlock $unlock): Content
    {
        $result = new DataSet();

        $this->begin();
        try {

            $isOwn = $this->isOwnLease($unlock);

            $isPossible = false;
            if ($isOwn) {
                $isPossible = $this->isUnlockingPossible($unlock);
            }

            if ($isPossible) {
                $result = $this->getUnlockAccess()->insert($unlock)->getData();
                $this->commit();
            }

            if (!$isPossible) {
                $this->rollBack();
            }

            $isSuccess = false;
            if ($isOwn && !$isPossible) {
                $isSuccess = $this->getUnlockAccess()->getRowCount() > 0;
            }

            if($isSuccess){
                $result->setSuccessStatus();
            }
        } catch (\Exception $e) {
            $this->rollBack();
        }

        return $result;
    }

    /**
     * @param IUnlock $unlock
     * @return array
     */
    private function isOwnLease(IUnlock $unlock): bool
    {
        $lease = (new Lease())
            ->setToken($unlock->getToken())
            ->setId($unlock->getLeaseId());

        $leaseAccess = $this->getLeaseAccess();
        $leaseAccess->getShutterId($lease);

        $isSuccess = $leaseAccess->isSuccess();
        $shutterCount = $leaseAccess->getRowCount();
        $isOwn = $shutterCount > 0 && $isSuccess;


        return $isOwn;
    }

    /**
     * @param IUnlock $unlock
     * @return bool
     */
    private function isUnlockingPossible(IUnlock $unlock): bool
    {
        $leaseSet = $this->getLeaseAccess()->getData();
        foreach ($leaseSet->next() as $element) {
            /** @var ILease $element */
            $unlock->setShutterId($element->getShutterId());
            break;
        }

        $unlockAccess = $this->getUnlockAccess();
        $unlockAccess->selectByShutter($unlock);

        $isSuccess = $unlockAccess->isSuccess();
        $unlockCount = $unlockAccess->getRowCount();
        $isPossible = $unlockCount == 0 && $isSuccess;


        return $isPossible;
    }


}
