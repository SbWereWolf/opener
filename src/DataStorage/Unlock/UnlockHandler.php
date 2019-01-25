<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 16.01.2019 5:23
 */

namespace DataStorage\Unlock;


use BusinessLogic\Basis\Content;
use BusinessLogic\Basis\DataSet;
use BusinessLogic\Unlock\IUnlock;
use DataStorage\Basis\DataHandler;

class UnlockHandler extends DataHandler
{
    private $unlockAccess = null;

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

            $unlockAccess = $this->getUnlockAccess();
            $unlockAccess->selectByShutter($unlock);

            $isSuccess = $unlockAccess->isSuccess();
            $rowCount = $unlockAccess->getRowCount();
            $isPossible = $rowCount == 0 && $isSuccess;

            if ($isPossible) {
                $result = $this->getUnlockAccess()->insert($unlock)->getData();

                $this->commit();
            }

            if (!$isPossible) {
                $this->rollBack();
            }

            if ($isSuccess && !$isPossible) {
                $isSuccess = $rowCount > 0;
            }

            if($isSuccess){
                $result->setSuccessStatus();
            }
        } catch (\Exception $e) {
            $this->rollBack();
        }

        return $result;
    }


}
