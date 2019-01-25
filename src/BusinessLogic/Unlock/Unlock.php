<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 16.01.2019 4:56
 */

namespace BusinessLogic\Unlock;


class Unlock implements IUnlock
{
    private $point = '';
    private $shutterId = 0;

    /**
     * @return string
     */
    public function getPoint(): string
    {
        return $this->point;
    }

    /**
     * @param string $point
     * @return Unlock
     */
    public function setPoint(string $point): IUnlock
    {
        $this->point = $point;
        return $this;
    }

    /**
     * @return int
     */
    public function getShutterId(): int
    {
        return $this->shutterId;
    }

    /**
     * @param int $shutterId
     * @return Unlock
     */
    public function setShutterId(int $shutterId): IUnlock
    {
        $this->shutterId = $shutterId;
        return $this;
    }
}
