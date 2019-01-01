<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 17:15
 */

namespace Environment;


class RouteSet
{
    private $handler = null;
    
    public function __construct(\Slim\App $app)
    {
        $this->handler = $app;
    }

    /**
     * @return \Slim\App
     * @throws \Exception
     */
    public function setUp(): \Slim\App
    {
        $app = $this->handler;

        $app = (new Lease($app))->settingUpRoutes()->getHandler();
        $app = (new Session($app))->settingUpRoutes()->getHandler();
        $app = (new Unlock($app))->settingUpRoutes()->getHandler();
        $app = (new User($app))->settingUpRoutes()->getHandler();

        return $app;
    }

}
