<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 26.01.2019 1:51
 */

namespace BusinessLogic\Lease;


use BusinessLogic\Basis\Content;
use BusinessLogic\Session\SessionHelper;

class LeaseProcess
{
    private $lease = null;

    public function __construct(ILease $lease)
    {
        $this->setLease($lease);
    }

    /**
     * @param string $dataPath
     * @param string $token
     * @return Content
     */
    public function addNewLease(string $dataPath, string $token): Content
    {
        $isSuccess = (new SessionHelper($dataPath))->prolong($token);

        $leaseSet = new LeaseSet();
        if ($isSuccess) {
            $leaseSet = (new LeaseManager($this->getLease(), $dataPath))->create();
        }
        return $leaseSet;
    }

    /**
     * @return ILease
     */
    public function getLease(): ILease
    {
        return $this->lease;
    }

    /**
     * @param ILease $lease
     * @return LeaseProcess
     */
    public function setLease(ILease $lease): self
    {
        $this->lease = $lease;
        return $this;
    }

    /**
     * @param string $dataPath
     * @return Content
     */
    public function overwriteLease(string $dataPath): Content
    {
        $leaseSet = (new LeaseManager($this->getLease(), $dataPath))->update();
        return $leaseSet;
    }

    /**
     * @param string $dataPath
     * @param string $token
     * @return Content
     */
    public function retrieveCurrent(string $dataPath, string $token): Content
    {
        $isSuccess = (new SessionHelper($dataPath))->prolong($token);

        $leaseSet = new LeaseSet();
        if ($isSuccess) {
            $leaseSet = (new LeaseManager($this->getLease(), $dataPath))->retrieveCurrent();
        }
        return $leaseSet;
    }

    /**
     * @param string $dataPath
     * @param string $token
     * @return Content
     */
    public function retrieveActual(string $dataPath, string $token): Content
    {
        $isSuccess = (new SessionHelper($dataPath))->prolong($token);

        $leaseSet = (new LeaseManager($this->getLease(), $dataPath))->retrieveActual();
        return $leaseSet;
    }
}
