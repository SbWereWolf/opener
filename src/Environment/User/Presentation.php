<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 03.01.2019 1:09
 */

namespace Environment\User;


use Environment\HttpCode;
use Presentation\UserSetView;
use Slim\Http\Response;

class Presentation extends \Environment\Presentation
{
    /**
     * @return Response
     */
    public function process(): Response
    {
        $response = (new HttpCode($this->getResponse(), $this->getRequest()))->process($this->isSuccess());

        return $response;
    }
}
