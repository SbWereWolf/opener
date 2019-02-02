<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 16.01.2019 4:56
 */

namespace BusinessLogic\Unlock;


/**
 * @SWG\Definition(
 *   definition="unlock",
 *   type="object",
 *   description="Task for unlocking",
 *   @SWG\Property(
 *       property="token",
 *       type="string",
 *   ),
 *   @SWG\Property(
 *       property="lease-id",
 *       type="integer",
 *   ),
 * )
 */
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

    public function setLeaseId(int $shutterId): IUnlock;

    public function getLeaseId(): int;

    public function setToken(string $token): IUnlock;

    public function getToken(): string;
}
