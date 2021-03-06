<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 18:04
 */

namespace BusinessLogic\Lease;


class Lease implements ILease
{
    private $id = 0;
    private $shutterId = 0;
    private $start = 0;
    private $finish = 0;
    private $occupancyTypeId = 0;
    private $token = '';
    private $userId = 0;

    /**
     * @param int $id
     * @return Lease
     */
    public function setId(int $id): ILease
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $shutterId
     * @return Lease
     */
    public function setShutterId(int $shutterId): ILease
    {
        $this->shutterId = $shutterId;
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
     * @param int $start
     * @return ILease
     */
    public function setStart(int $start): ILease
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * @param int $finish
     * @return ILease
     */
    public function setFinish(int $finish): ILease
    {
        $this->finish = $finish;
        return $this;
    }

    /**
     * @return int
     */
    public function getFinish(): int
    {
        return $this->finish;
    }

    /**
     * @param int $occupancyTypeId
     * @return ILease
     */
    public function setOccupancyTypeId(int $occupancyTypeId): ILease
    {
        $this->occupancyTypeId = $occupancyTypeId;
        return $this;
    }

    /**
     * @return int
     */
    public function getOccupancyTypeId(): int
    {
        return $this->occupancyTypeId;
    }

    /**
     * @param string $token
     * @return Lease
     */
    public function setToken(string $token): ILease
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param int $userId
     * @return Lease
     */
    public function setUserId(int $userId): ILease
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
