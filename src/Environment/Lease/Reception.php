<?php

namespace Environment\Lease;

use LanguageFeatures\ArrayParser;
use Latch\ILease;
use Latch\Lease;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Reception extends \Environment\Reception
{
    const ID = 'id';
    const USER_ID = 'user-id';
    const SHUTTER_ID = 'shutter-id';
    const START = 'start';
    const FINISH = 'finish';
    const OCCUPANCY_TYPE_ID = 'occupancy-type-id';
    const TOKEN = 'token';

    private function getToken(): string
    {
        $value = $this->getParser()->getStringField(self::TOKEN);
        return $value;
    }

    private function getId(): int
    {
        $value = $this->getParser()->getIntegerField(self::ID);
        return $value;
    }

    private  function getUserId(): int
    {
        $value = $this->getParser()->getIntegerField(self::USER_ID);
        return $value;
    }

    private  function getShutterId(): int
    {
        $value = $this->getParser()->getIntegerField(self::SHUTTER_ID);
        return $value;
    }

    private  function getStart(): int
    {
        $value = $this->getParser()->getIntegerField(self::START);
        return $value;
    }

    private  function getFinish(): int
    {
        $value = $this->getParser()->getIntegerField(self::FINISH);
        return $value;
    }

    private  function getOccupancyTypeId(): int
    {
        $value = $this->getParser()->getIntegerField(self::OCCUPANCY_TYPE_ID);
        return $value;
    }

    public function toCreate(): ILease
    {
        $item = $this->setupFromBody();

        return $item;
    }

    /**
     * @return Lease
     */
    public function toRead(): ILease
    {
        $item = $this->setupFromPath();

        return $item;
    }

    public function toDelete(): ILease
    {
        $item = $this->setupFromPath();

        return $item;
    }

    public function toUpdate(): ILease
    {
        $item = $this->setupFromBody();

        return $item;
    }

    private function setupFromBody(): ILease
    {
        $body = $this->getRequest()->getParsedBody();
        $this->setParser(new ArrayParser($body));

        $lease = $this->setUpLease();

        return $lease;
    }

    private function setupFromPath(): ILease
    {
        $this->setParser(new ArrayParser($this->getArguments()));

        $lease = $this->setUpLease();

        return $lease;
    }

    private function setUpLease(): ILease
    {
        $id = $this->getId();
        $userId = $this->getUserId();
        $shutterId = $this->getShutterId();
        $start = $this->getStart();
        $finish = $this->getFinish();
        $occupancyTypeId = $this->getOccupancyTypeId();
        $token = $this->getToken();

        $lease = (new Lease())
            ->setId($id)
            ->setUserId($userId)
            ->setShutterId($shutterId)
            ->setStart($start)
            ->setFinish($finish)
            ->setOccupancyTypeId($occupancyTypeId)
            ->setToken($token);
        return $lease;
    }
}
