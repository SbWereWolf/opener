<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment\Lease;


use Environment\Basis\Routing;
use Slim\Http\Request;
use Slim\Http\Response;

class Router extends \Environment\Basis\Router
{
    private $root = '/lease/';
    private $token = '{token}/';

    public function settingUpRoutes(): Routing
    {
        $app = $this->getHandler();
        $dataSource = $this->getDataSource();
        $root = $this->root;
        $token = $this->token;
        /**
         * @SWG\Get(
         *    path="/lease/actual/",
         *    summary="Browse whole collection of actual lease suggestion",
         *    description="Shutter free for now",
         *    @SWG\Response(
         *        response=200,
         *        description="Successful operation",
         *        @SWG\Schema(
         *            type="array",
         *            @SWG\Items(ref="#/definitions/shutter-id")
         *        ),
         *    ),
         * )
         */
        /**
         * @SWG\Get(
         *    path="/lease/actual/{token}/",
         *    summary="Browse whole collection of actual lease suggestion",
         *    description="Shutter free for now",
         *    @SWG\Parameter(
         *        name="token",
         *        in="path",
         *        type="string",
         *        required=false,
         *    ),
         *    @SWG\Response(
         *        response=200,
         *        description="Successful operation",
         *        @SWG\Schema(
         *            type="array",
         *            @SWG\Items(ref="#/definitions/shutter-id")
         *        ),
         *    ),
         * )
         */
        $app->get($root . "actual/[$token]", function (Request $request, Response $response, array $arguments)
        use ($dataSource) {
            $response = (new Controller($request, $response, $arguments, $dataSource))
                ->letRetrieveActual()
                ->process();

            return $response;
        });
        /**
         * @SWG\Post(
         *    path="/lease/",
         *    summary="Create lease",
         *    description="Create new lease",
         *    @SWG\Parameter(
         *        name="lease",
         *        in="body",
         *        description="properties of lease for create",
         *        required=true,
         *        @SWG\Schema(ref="#/definitions/lease-with-token"),
         *    ),
         *    @SWG\Response(
         *        response=201,
         *        description="Successful operation",
         *    ),
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
         *    path="/lease/current/{token}/",
         *     summary="Browse whole collection of own leases of session user",
         *    description="Lease suggestion with finish time greater than now and token equal that given token",
         *     @SWG\Parameter(
         *         name="token",
         *         in="path",
         *         type="string",
         *         description="Token of users session",
         *         required=true,
         *     ),
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
        $app->get($root . "current/$token", function (Request $request, Response $response, array $arguments)
        use ($dataSource) {
            $response = (new Controller($request, $response, $arguments, $dataSource))
                ->letRetrieveCurrent()
                ->process();

            return $response;
        });
        /**
         * @SWG\Put(
         *    path="/lease/",
         *     summary="Override lease",
         *    description="Redefine properties of lease",
         *     @SWG\Parameter(
         *         name="lease",
         *         in="body",
         *         description="properties of lease for override",
         *         required=true,
         *         @SWG\Schema(ref="#/definitions/lease-with-id"),
         *     ),
         *     @SWG\Response(
         *         response=200,
         *         description="Successful operation",
         *     ),
         * )
         */
        $app->put($root, function (Request $request, Response $response, array $arguments)
        use ($dataSource) {

            $response = (new Controller($request, $response, $arguments, $dataSource))
                ->process();

            return $response;
        });

        return $this;
    }
}
