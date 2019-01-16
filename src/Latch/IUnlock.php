<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 16.01.2019 4:56
 */

namespace Latch;


interface IUnlock
{
    public function setPoint(string $point): IUnlock;

    /**
     * @return string
     */
    public function getPoint(): string;

    /**
     * @param int $shutterId
     * @return Unlock
     */
    public function setShutterId(int $shutterId): IUnlock;

    /**
     * @return int
     */
    public function getShutterId(): int;
}
