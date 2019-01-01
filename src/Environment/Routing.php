<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment;


use Slim\Http\Request;
use Slim\Http\Response;

class Routing implements EndPoint
{
    private $handler = null;

    public function __construct($app = null)
    {
        $isValid = ($app instanceof \Slim\App) && !empty($app);
        if ($isValid) {
            $this->handler = $app;
        }
    }

    public function initialize(): bool
    {
        $app = $this->handler;
        $isValid = ($app instanceof \Slim\App) && !empty($app);

        if ($isValid) {
            $app->post(self::USER, function (Request $request, Response $response, array $arguments) {
                /*
                 * email
                 * password
                 * */
                return $response;
            });

            $app->post(self::SESSION, function (Request $request, Response $response, array $arguments) {
                /*
                 * email
                 * password
                 * вернёт токен сессии
                 * */
                return $response;
            });
            $app->delete(self::SESSION, function (Request $request, Response $response, array $arguments) {
                /*
                 * token
                 */
                return $response;
            });

            $app->get(self::LEASE, function (Request $request, Response $response, array $arguments) {

                return $response;
            });

            $app->post(self::LEASE, function (Request $request, Response $response, array $arguments) {
                /*
                 * token
                 * shutter-id
                 * */
                return $response;
            });

            $app->get(self::LEASE . self::TOKEN, function (Request $request, Response $response, array $arguments) {
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

            $app->put(self::LEASE, function (Request $request, Response $response, array $arguments) {
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

            $app->post(self::UNLOCK, function (Request $request, Response $response, array $arguments) {
                /*
                 * shutter-id
                 * token
                 * */
                return $response;
            });

            $app->get(self::UNLOCK . self::POINT, function (Request $request, Response $response, array $arguments) {

                return $response;
            });

            $app->delete(self::UNLOCK, function (Request $request, Response $response, array $arguments) {
                /* point */
                return $response;
            });
        }

        return true;
    }

}
