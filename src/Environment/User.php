<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment;


use Slim\Http\Request;
use Slim\Http\Response;

class User extends Router
{
    private $root = '/user/';

    public function __construct(\Slim\App $app)
    {
        parent::__construct($app);
    }

    public function settingUpRoutes(): Routing
    {
        $app = $this->getHandler();
        $root = $this->root;

        $app->post($root, function (Request $request, Response $response, array $arguments) {
            /*
             * email
             * password
             * */
            return $response;
        });

        return $this;
    }

}
