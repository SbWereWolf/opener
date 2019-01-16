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
    public function process(bool $isSuccess): Response;
}
