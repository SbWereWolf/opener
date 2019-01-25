<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 16:53
 */

namespace Environment\Basis;


class Router implements Routing
{
    private $handler = null;

    public function __construct(\Slim\App $app)
    {
        $this->handler = $app;
    }

    /**
     * @return \Slim\App
     */
    public function getHandler(): \Slim\App
    {
        return $this->handler;
    }

    /** @throws \Exception Method Not Implemented */
    public function settingUpRoutes(): Routing
    {
        throw new \Exception('Method settingUpRoutes() Not Implemented');
    }
}
