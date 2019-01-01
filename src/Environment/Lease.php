<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment;


use Latch\CheckInLogbook;
use Presentation\HaspSetView;
use Slim\Http\Request;
use Slim\Http\Response;

class Lease extends Router
{
    private $root = '/lease/';
    private $token = '{token}/';

    public function __construct(\Slim\App $app)
    {
        parent::__construct($app);
    }

    public function settingUpRoutes(): Routing
    {
        $app = $this->getHandler();
        $root = $this->root;
        $token = $this->token;

        $app->get($root, function (Request $request, Response $response, array $arguments) {
            $haspSet = (new CheckInLogbook())->getActual();
            $json = (new HaspSetView($haspSet))->toJson();

            $response = $response->withJson($json)->withStatus(200);

            return $response;
        });

        $app->post($root, function (Request $request, Response $response, array $arguments) {
            /*
             * token
             * shutter-id
             * */
            return $response;
        });

        $app->get("$root$token", function (Request $request, Response $response, array $arguments) {
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
