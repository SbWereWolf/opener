<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment\Session;


use Slim\Http\Request;
use Slim\Http\Response;

class Router extends \Environment\Router
{
    private $root = '/session/';

    public function settingUpRoutes(): \Environment\Routing
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
