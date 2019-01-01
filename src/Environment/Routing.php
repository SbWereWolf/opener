<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 4:48
 */

namespace Environment;


interface Routing
{
    public function getHandler(): \Slim\App;
    public function settingUpRoutes(): Routing;

}
