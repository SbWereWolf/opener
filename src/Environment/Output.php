<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 03.01.2019 0:50
 */

namespace Environment;


use Slim\Http\Response;

interface Output
{
    public function isSuccess();
    public function getResponse():Response;
    public function attachContent();
}
