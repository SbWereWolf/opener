<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 16:53
 */

namespace Environment\Basis;


use DataStorage\Basis\DataSource;

class Router implements Routing
{
    private $handler = null;
    private $dataSource = null;

    public function __construct(\Slim\App $app)
    {
        $this->handler = $app;
        $this->dataSource = $app->getContainer()->get('settings')[DATA_SOURCE_KEY];
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

    /**
     * @return null
     */
    public function getDataSource(): DataSource
    {
        return $this->dataSource;
    }
}
