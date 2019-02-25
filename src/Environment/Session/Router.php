<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment\Session;


use Environment\Basis\Routing;
use Slim\Http\Request;
use Slim\Http\Response;

class Router extends \Environment\Basis\Router
{
    private $root = '/session/';
    private $token = '{token}/';

    public function settingUpRoutes(): Routing
    {
        $app = $this->getHandler();
        $dataSource = $this->getDataSource();
        $root = $this->root;
        $token = $this->token;
        /**
         * @SWG\Delete(
         *     path="/session/{token}/",
         *     summary="Finish session",
         *     description="Finish working session of user",
         *     @SWG\Parameter(
         *         name="token",
         *         in="path",
         *         type="string",
         *         description="token of working session of user",
         *         required=true,
         *     ),
         *     @SWG\Response(
         *         response=204,
         *         description="Successful operation",
         *     ),
         * )
         */
        $app->delete("$root$token", function (Request $request, Response $response, array $arguments)
        use ($dataSource) {

            $response = (new Controller($request, $response, $arguments, $dataSource))
                ->process();

            return $response;
        });

        return $this;
    }
}
