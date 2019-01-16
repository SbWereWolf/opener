<?php

namespace Environment;

use Slim\Http\Response;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:52
 */
interface IHttpCode
{
    const COMMON_OK = 200;
    const POST_OK = 201;
    const DELETE_OK = 204;
    const ERROR = 500;

    public function process(bool $isSuccess): Response;
}
