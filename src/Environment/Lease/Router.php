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
            $response = (new Controller($request, $response, $arguments, DATA_PATH))
                ->process();

            return $response;
        });
        /**
         * @SWG\Post(
         *    path="/lease/",
         *     summary="Create lease",
         *    description="Create new lease",
         *     @SWG\Response(
         *         response=201,
         *         description="Successful operation",
         *         @SWG\Schema(
         *             type="array",
         *             @SWG\Items(ref="#/definitions/lease-with-id")
         *         ),
         *     ),
         *     @SWG\Parameter(
         *         name="lease",
         *         in="body",
         *         description="properties of lease for create",
         *         required=true,
         *         @SWG\Schema(ref="#/definitions/lease"),
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
         *    path="/lease/{token}/",
         *     summary="Browse whole collection of own leases of session user",
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
            $response = (new Controller($request, $response, $arguments, DATA_PATH))
                ->process();

            return $response;
        });
        /**
         * @SWG\Put(
         *    path="/lease/",
         *     summary="Override lease",
         *    description="Redefine properties of lease",
         *     @SWG\Response(
         *         response=200,
         *         description="Successful operation",
         *     ),
         *     @SWG\Parameter(
         *         name="lease",
         *         in="body",
         *         description="properties of lease for override",
         *         required=true,
         *         @SWG\Schema(ref="#/definitions/lease-with-id"),
         *     ),
         * )
         */
        $app->put($root, function (Request $request, Response $response, array $arguments) {

            $response = (new Controller($request, $response, $arguments, DATA_PATH))
                ->process();

            return $response;
        });

        return $this;
    }
}
