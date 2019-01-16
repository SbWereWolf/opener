<?php

namespace Environment\Unlock;

use LanguageFeatures\ArrayParser;
use Latch\IUnlock;
use Latch\Unlock;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Reception extends \Environment\Reception
{
    const POINT = 'point';
    const SHUTTER_ID = 'shutter-id';

    private static function getShutterId(ArrayParser $parser): int
    {
        $value = $parser->getIntegerField(self::SHUTTER_ID);
        return $value;
    }

    private static function getPoint(ArrayParser $parser): string
    {
        $value = $parser->getStringField(self::POINT);
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
        $parser = new ArrayParser($body);

        $shutterId = $this->getShutterId($parser);

        $item = (new Unlock())
            ->setShutterId($shutterId);

        return $item;
    }

    private function setupFromPath(): IUnlock
    {
        $arguments = $this->getArguments();
        $parser = new ArrayParser($arguments);

        $point = $this->getPoint($parser);
        $item = (new Unlock())->setPoint($point);

        return $item;
    }
}
