<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 18:04
 */

namespace Latch;


interface ILease
{
    /**
     * @param int $id
     * @return Lease
     */
    public function setId(int $id): Lease;

    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @param int $userId
     * @return Lease
     */
    public function setUserId(int $userId): Lease;

    /**
     * @return int
     */
    public function getUserId(): int;

    /**
     * @param int $shutterId
     * @return Lease
     */
    public function setShutterId(int $shutterId): Lease;

    /**
     * @return int
     */
    public function getShutterId(): int;

    /**
     * @param string $start
     * @return Lease
     */
    public function setStart(int $start): Lease;

    /**
     * @return string
     */
    public function getStart(): int;

    /**
     * @param string $finish
     * @return Lease
     */
    public function setFinish(int $finish): Lease;

    /**
     * @return string
     */
    public function getFinish(): int;

    /**
     * @param int $occupancyTypeId
     * @return Lease
     */
    public function setOccupancyTypeId(int $occupancyTypeId): Lease;

    /**
     * @return int
     */
    public function getOccupancyTypeId(): int;

    /**
     * @param string $token
     * @return Lease
     */
    public function setToken(string $token): Lease;

    /**
     * @return string
     */
    public function getToken(): string;
}
