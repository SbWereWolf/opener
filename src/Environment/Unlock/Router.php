<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment\Unlock;


use Environment\Routing;
use Slim\Http\Request;
use Slim\Http\Response;

class Router extends \Environment\Router
{
    private $root = '/unlock/';
    private $point = '{point}/';

    public function settingUpRoutes(): Routing
    {
        $app = $this->getHandler();
        $root = $this->root;
        $point = $this->point;

        $app->post($root, function (Request $request, Response $response, array $arguments) {
            /*
             * shutter-id
             * token
             * */
            return $response;
        });

        $app->get("$root$point", function (Request $request, Response $response, array $arguments) {

            return $response;
        });

        $app->delete($root, function (Request $request, Response $response, array $arguments) {
            /* point */
            return $response;
        });
        return $this;
    }
}
