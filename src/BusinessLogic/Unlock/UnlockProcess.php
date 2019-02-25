<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 26.01.2019 1:51
 */

namespace BusinessLogic\Unlock;


use BusinessLogic\Basis\Content;
use BusinessLogic\Basis\DataSet;
use BusinessLogic\Session\SessionHelper;
use DataStorage\Basis\DataSource;

class UnlockProcess
{
    private $unlock = null;

    public function __construct(IUnlock $lease)
    {
        $this->setUnlock($lease);
    }

    /**
     * @param string $dataPath
     * @param string $token
     * @return Content
     */
    public function scheduleUnlock(DataSource $dataPath, string $token): Content
    {
        $isSuccess = (new SessionHelper($dataPath))->prolong($token);

        $result = new DataSet();
        if ($isSuccess) {
            $result = (new UnlockManager($this->getUnlock(), $dataPath))->scheduleUnlock();
        }

        return $result;
    }

    public function getUnlock(): IUnlock
    {
        return $this->unlock;
    }

    public function setUnlock(IUnlock $unlock): self
    {
        $this->unlock = $unlock;
        return $this;
    }

    public function confirmUnlock(DataSource $dataPath): Content
    {
        $result = (new UnlockManager($this->getUnlock(), $dataPath))->confirmUnlock();

        return $result;
    }


    public function checkPoint(DataSource $dataPath): Content
    {
        $result = (new UnlockManager($this->getUnlock(), $dataPath))->checkPoint();

        return $result;
    }

}
