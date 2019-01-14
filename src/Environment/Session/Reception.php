<?php

namespace Environment\Session;

use LanguageFeatures\ArrayParser;
use Latch\ISession;
use Latch\Session;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Reception extends \Environment\Reception
{
    const TOKEN = 'token';

    private function getToken(): string
    {
        $value = $this->getParser()->getStringField(self::TOKEN);
        return $value;
    }

    public function toDelete(): ISession
    {
        $item = $this->setupFromPath();

        return $item;
    }

    private function setupFromPath(): ISession
    {
        $this->setParser(new ArrayParser($this->getArguments()));

        $lease = $this->setupSession();

        return $lease;
    }

    private function setupSession(): ISession
    {
        $token = $this->getToken();

        $session = (new Session())
            ->setToken($token);

        return $session;
    }
}
