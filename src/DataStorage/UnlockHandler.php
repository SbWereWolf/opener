<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 16.01.2019 5:23
 */

namespace DataStorage;


use Latch\Content;
use Latch\IUnlock;
use Latch\UnlockSet;

class UnlockHandler extends DataHandler
{
    private $unlockAccess = null;

    public function read(IUnlock $unlock): Content
    {
        $result = $this->getUnlockAccess()->selectByPoint($unlock)->getData();
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

    public function delete(IUnlock $lease): Content
    {
        $result = $this->getUnlockAccess()->delete($lease)->getData();

        return $result;
    }

    public function create(IUnlock $unlock): Content
    {
        $result = new UnlockSet();

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
