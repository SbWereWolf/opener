<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment;


use Slim\Http\Request;
use Slim\Http\Response;

class Session extends Router
{
    private $root = '/session/';

    public function __construct(\Slim\App $app)
    {
        parent::__construct($app);
    }

    public function settingUpRoutes(): Routing
    {
        $app = $this->getHandler();
        $root = $this->root;

        $app->post($root, function (Request $request, Response $response, array $arguments) {
            /*
             * email
             * password
             * вернёт токен сессии
             * */
            return $response;
        });
        $app->delete($root, function (Request $request, Response $response, array $arguments) {
            /*
             * token
             */
            return $response;
        });
        return $this;
    }
}
