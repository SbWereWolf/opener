<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 03.01.2019 0:50
 */

namespace Environment\Basis;


use Slim\Http\Response;

interface IPresentation
{
    public function getResponse():Response;

    public function process(): Response;
}
