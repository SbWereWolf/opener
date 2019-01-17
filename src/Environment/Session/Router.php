<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment\Session;


use Slim\Http\Request;
use Slim\Http\Response;

class Router extends \Environment\Router
{
    private $root = '/session/';
    private $token = '{token}/';

    public function settingUpRoutes(): \Environment\Routing
    {
        $app = $this->getHandler();
        $root = $this->root;
        $token = $this->token;
        /**
         * @SWG\Delete(
         *    path="/session/{token}/",
         *     summary="Finish session",
         *    description="Finish working session of user",
         *     @SWG\Response(
         *         response=204,
         *         description="Successful operation",
         *     ),
         *     @SWG\Parameter(
         *         name="token",
         *         in="path",
         *         type="string",
         *         description="token of working session of user",
         *         required=true,
         *     ),
         * )
         */
        $app->delete("$root$token", function (Request $request, Response $response, array $arguments) {

            $response = (new Controller($request, $response, $arguments, DATA_PATH))
                ->process();

            return $response;
        });

        return $this;
    }
}
