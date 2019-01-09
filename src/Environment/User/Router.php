<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment\User;


use Environment\Routing;
use Slim\Http\Request;
use Slim\Http\Response;

class Router extends \Environment\Router
{
    private $root = '/user/';

    public function settingUpRoutes(): Routing
    {
        $app = $this->getHandler();
        $root = $this->root;
        /**
         * @SWG\Post(
         *    path="/user/",
         *     summary="Create user",
         *    description="Create new user",
         *     @SWG\Response(
         *         response=201,
         *         description="Successful operation",
         *         @SWG\Schema(
         *             type="array",
         *             @SWG\Items(ref="#/definitions/user-with-id")
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

        return $this;
    }

}
