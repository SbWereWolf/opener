<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment\User;


use Environment\Basis\Routing;
use Slim\Http\Request;
use Slim\Http\Response;

class Router extends \Environment\Basis\Router
{
    private $root = '/user/';

    public function settingUpRoutes(): Routing
    {
        $app = $this->getHandler();
        $root = $this->root;
        $login = Controller::LOG_IN;
        /**
         * @SWG\Post(
         *    path="/user/",
         *     summary="Create a user",
         *    description="Create new user",
         *     @SWG\Response(
         *         response=201,
         *         description="Successful operation",
         *         @SWG\Schema(
         *             type="array",
         *             @SWG\Items(ref="#/definitions/user")
         *         ),
         *     ),
         *     @SWG\Parameter(
         *         name="user",
         *         in="body",
         *         description="properties of user for create",
         *         required=true,
         *         @SWG\Schema(ref="#/definitions/user"),
         *     ),
         * )
         */
        $app->post($root, function (Request $request, Response $response, array $arguments) {

            $response = (new Controller($request, $response, $arguments, DATA_PATH))
                ->process();

            return $response;
        });
        /**
         * @SWG\Post(
         *    path="/user/login/",
         *     summary="Open session",
         *    description="Open working session for user",
         *     @SWG\Response(
         *         response=201,
         *         description="Successful operation",
         *         @SWG\Schema(
         *             type="array",
         *             @SWG\Items(ref="#/definitions/session")
         *         ),
         *     ),
         *     @SWG\Parameter(
         *         name="user",
         *         in="body",
         *         description="properties of user for create session",
         *         required=true,
         *         @SWG\Schema(ref="#/definitions/user"),
         *     ),
         * )
         */
        $app->post("$root$login", function (Request $request, Response $response, array $arguments) {

            $response = (new Controller($request, $response, $arguments, DATA_PATH))
                ->process();

            return $response;
        });

        return $this;
    }

}
