<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment\Storage;


use Environment\Basis\Routing;
use Slim\Http\Request;
use Slim\Http\Response;

class Router extends \Environment\Basis\Router
{
    private $root = '/storage';

    public function settingUpRoutes(): Routing
    {
        $app = $this->getHandler();
        $root = $this->root;
        /**
         * @SWG\Post(
         *     path="/storage/install/",
         *     summary="Create a database",
         *     description="Creates all tables",
         *     @SWG\Response(
         *         response=201,
         *         description="Null response"
         *     )
         * )
         */
        $app->post("$root/install/", function (Request $request, Response $response, array $arguments) {
            $response = (new Controller($request, $response, $arguments, DATA_PATH))
                ->process();

            return $response;
        });
        /**
         * @SWG\Delete(
         *     path="/storage/dismount/",
         *     summary="Unmount database",
         *     description="Drops all tables",
         *     @SWG\Response(
         *         response=200,
         *         description="Null response"
         *     )
         * )
         */
        $app->delete("$root/dismount/", function (Request $request, Response $response, array $arguments) {
            $response = (new Controller($request, $response, $arguments, DATA_PATH))
                ->process();

            return $response;
        });

        return $this;
    }
}
