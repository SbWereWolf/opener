<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 17:15
 */

namespace Environment\Setup;

/**
 * @SWG\Swagger(
 *   schemes={"http"},
 *   host="local.opener",
 *   basePath="/",
 *   produces={"application/json"},
 *   consumes={"application/json"},
 *     @SWG\Info(
 *         version="0.1.0",
 *         title="opener",
 *         description="API that uses an opener",
 *         @SWG\License(name="GPL")
 *     ),
 * )
 */
class Setup
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
    public function perform(): \Slim\App
    {
        $app = $this->handler;

        $app = (new \Environment\Lease\Router($app))->settingUpRoutes()->getHandler();
        $app = (new \Environment\Session\Router($app))->settingUpRoutes()->getHandler();
        $app = (new \Environment\Unlock\Router($app))->settingUpRoutes()->getHandler();
        $app = (new \Environment\User\Router($app))->settingUpRoutes()->getHandler();
        $app = (new \Environment\Storage\Router($app))->settingUpRoutes()->getHandler();

        return $app;
    }

}
