<?php

namespace Environment\Unlock;


use BusinessLogic\Unlock\IUnlock;
use BusinessLogic\Unlock\Unlock;
use LanguageFeatures\ArrayParser;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Reception extends \Environment\Basis\Reception
{
    const POINT = 'point';
    const LEASE_ID = 'lease-id';
    const TOKEN = 'token';

    private function getLeaseId(): int
    {
        $value = $this->getParser()->getIntegerField(self::LEASE_ID);
        return $value;
    }

    private function getToken(): string
    {
        $value = $this->getParser()->getStringField(self::TOKEN);
        return $value;
    }

    private function getPoint(): string
    {
        $value = $this->getParser()->getStringField(self::POINT);
        return $value;
    }

    public function toCreate(): IUnlock
    {
        $item = $this->setupFromBody();

        return $item;
    }

    /**
     * @return IUnlock
     */
    public function toRead(): IUnlock
    {
        $item = $this->setupFromPath();

        return $item;
    }

    public function toDelete(): IUnlock
    {
        $item = $this->setupFromPath();

        return $item;
    }

    private function setupFromBody(): IUnlock
    {
        $body = $this->getRequest()->getParsedBody();
        $this->setParser(new ArrayParser($body));

        $leaseId = $this->getLeaseId();
        $token = $this->getToken();

        $item = (new Unlock())
            ->setLeaseId($leaseId)
            ->setToken($token);

        return $item;
    }

    private function setupFromPath(): IUnlock
    {
        $arguments = $this->getArguments();
        $this->setParser(new ArrayParser($arguments));

        $point = $this->getPoint();
        $item = (new Unlock())->setPoint($point);

        return $item;
    }
}
