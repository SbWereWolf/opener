<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment\User;


use Slim\Http\Request;
use Slim\Http\Response;

class Router extends \Environment\Router
{
    private $root = '/user/';

    public function settingUpRoutes(): \Environment\Routing
    {
        $app = $this->getHandler();
        $root = $this->root;

        $app->post($root, function (Request $request, Response $response, array $arguments) {
            /*
             * email
             * password
             * */
            return $response;
        });

        return $this;
    }

}
