<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 18:04
 */

namespace Latch;


class Lease implements ILease
{
    private $id = 0;
    private $userId = 0;
    private $shutterId = 0;
    private $start = 0;
    private $finish = 0;
    private $occupancyTypeId = 0;
    private $token = '';

    /**
     * @param int $id
     * @return Lease
     */
    public function setId(int $id): self
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
     * @param int $userId
     * @return Lease
     */
    public function setUserId(int $userId): self
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

    /**
     * @param int $shutterId
     * @return Lease
     */
    public function setShutterId(int $shutterId): self
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
     * @param string $start
     * @return Lease
     */
    public function setStart(int $start): self
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return string
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * @param string $finish
     * @return Lease
     */
    public function setFinish(int $finish): self
    {
        $this->finish = $finish;
        return $this;
    }

    /**
     * @return string
     */
    public function getFinish(): int
    {
        return $this->finish;
    }

    /**
     * @param int $occupancyTypeId
     * @return Lease
     */
    public function setOccupancyTypeId(int $occupancyTypeId): self
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
    public function setToken(string $token): self
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
}
