<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment\Unlock;


use Environment\Basis\Routing;
use Slim\Http\Request;
use Slim\Http\Response;

class Router extends \Environment\Basis\Router
{
    private $root = '/unlock/';
    private $point = '{point}/';

    public function settingUpRoutes(): Routing
    {
        $app = $this->getHandler();
        $dataSource = $this->getDataSource();
        $root = $this->root;
        $point = $this->point;
        /**
         * @SWG\Post(
         *     path="/unlock/",
         *     summary="Schedule unlock",
         *     description="Schedule unlock of given lease",
         *     @SWG\Parameter(
         *         name="unlock",
         *         in="body",
         *         description="properties of unlocking task",
         *         required=true,
         *         @SWG\Schema(ref="#/definitions/unlock"),
         *     ),
         *     @SWG\Response(
         *         response=201,
         *         description="Successful operation",
         *     ),
         * )
         */
        $app->post($root, function (Request $request, Response $response, array $arguments)
        use ($dataSource) {

            $response = (new Controller($request, $response, $arguments, $dataSource))
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
        $app->get("$root$point", function (Request $request, Response $response, array $arguments)
        use ($dataSource) {

            $response = (new Controller($request, $response, $arguments, $dataSource))
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
        $app->delete("$root$point", function (Request $request, Response $response, array $arguments)
        use ($dataSource) {

            $response = (new Controller($request, $response, $arguments, $dataSource))
                ->process();

            return $response;
        });
        return $this;
    }
}
