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
        /**
         * @SWG\Post(
         *    path="/unlock/",
         *     summary="Schedule unlock",
         *    description="Schedule unlock of given point",
         *     @SWG\Parameter(
         *         name="shutter-id",
         *         in="body",
         *         description="properties of content for update",
         *         required=true,
         *         @SWG\Schema(ref="#/definitions/shutter-id"),
         *     ),
         *     @SWG\Response(
         *         response=201,
         *         description="Successful operation",
         *     ),
         * )
         */
        $app->post($root, function (Request $request, Response $response, array $arguments) {

            $response = (new Controller($request, $response, $arguments, DATA_PATH))
                ->process();

            return $response;
        });
        /**
         * @SWG\Get(
         *    path="/unlock/{point}/",
         *     summary="Request unlock",
         *    description="Request unlock of given point",
         *     @SWG\Parameter(
         *         name="point",
         *         in="path",
         *         type="string",
         *         description="Address-point of shutter",
         *         required=true,
         *     ),
         *     @SWG\Response(
         *         response=200,
         *         description="Should unlock",
         *     ),
         *     @SWG\Response(
         *         response=404,
         *         description="Should not unlock",
         *     ),
         * )
         */
        $app->get("$root$point", function (Request $request, Response $response, array $arguments) {

            $response = (new Controller($request, $response, $arguments, DATA_PATH))
                ->process();

            return $response;
        });
        /**
         * @SWG\Delete(
         *    path="/unlock/{point}/",
         *     summary="Confirm unlock",
         *    description="Confirm unlock of given point",
         *     @SWG\Parameter(
         *         name="point",
         *         in="path",
         *         type="string",
         *         description="Address-point of shutter",
         *         required=true,
         *     ),
         *     @SWG\Response(
         *         response=204,
         *         description="Successful operation",
         *     ),
         * )
         */
        $app->delete("$root$point", function (Request $request, Response $response, array $arguments) {

            $response = (new Controller($request, $response, $arguments, DATA_PATH))
                ->process();

            return $response;
        });
        return $this;
    }
}
