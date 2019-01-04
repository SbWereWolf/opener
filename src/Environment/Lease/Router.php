<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment\Lease;


use Environment\Routing;
use Slim\Http\Request;
use Slim\Http\Response;

class Router extends \Environment\Router
{
    private $root = '/lease/';
    private $token = '{token}/';

    public function settingUpRoutes(): Routing
    {
        $app = $this->getHandler();
        $root = $this->root;
        $token = $this->token;
        /**
         * @SWG\Get(
         *    path="/lease/",
         *     summary="Browse whole collection of actual lease suggestion",
         *    description="Lease suggestion with finish time greater than now",
         *     @SWG\Response(
         *         response=200,
         *         description="Successful operation",
         *         @SWG\Schema(
         *             type="array",
         *             @SWG\Items(ref="#/definitions/lease")
         *         ),
         *     ),
         * )
         */
        $app->get($root, function (Request $request, Response $response, array $arguments) {
            $response = (new Controller($request, $response, $arguments, Controller::GET, DATA_PATH))
                ->process();

            return $response;
        });

        $app->post($root, function (Request $request, Response $response, array $arguments) {
            /*
             * token
             * shutter-id
             * */
            return $response;
        });
        /**
         * @SWG\Get(
         *    path="/lease/{token}/",
         *     summary="Browse whole collection of session user own leases",
         *    description="Lease suggestion with finish time greater than now and token equal that given token",
         *     @SWG\Response(
         *         response=200,
         *         description="Successful operation",
         *         @SWG\Schema(
         *             type="array",
         *             @SWG\Items(ref="#/definitions/lease")
         *         ),
         *     ),
         *     @SWG\Parameter(
         *         name="token",
         *         in="path",
         *         type="string",
         *         description="Token of users session",
         *         required=true,
         *     ),
         * )
         */
        $app->get("$root$token", function (Request $request, Response $response, array $arguments) {
            $response = (new Controller($request, $response, $arguments, Controller::GET, DATA_PATH))
                ->process();

            return $response;
        });

        $app->put($root, function (Request $request, Response $response, array $arguments) {
            /*
             * id
             * user-id
             * shutter-id
             * start
             * finish
             * occupancy-code
             * */
            return $response;
        });

        return $this;
    }
}
