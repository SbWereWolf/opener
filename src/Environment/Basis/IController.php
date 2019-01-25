<?php

namespace Environment\Basis;

use Slim\Http\Response;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
interface IController
{
    public function process(): Response;
}
