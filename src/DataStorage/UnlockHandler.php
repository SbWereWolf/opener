<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 16.01.2019 5:23
 */

namespace DataStorage;


use Latch\Content;
use Latch\DataSet;
use Latch\IUnlock;

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
     * @return string
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
            $this->getUnlockAccess()->selectByShutter($unlock);

            $isPossible = $this->getUnlockAccess()->getRowCount() == 0;

            if ($isPossible) {
                $this->getUnlockAccess()->insert($unlock);
            }

            $this->commit();
        } catch (\Exception $e) {
            $this->rollBack();
        }

        return $result;
    }


}
