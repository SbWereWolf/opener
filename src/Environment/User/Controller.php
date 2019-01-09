<?php

namespace Environment\User;


use Latch\UserManager;
use Slim\Http\Response;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Controller extends \Environment\Controller
{
    public function process(): Response
    {
        $request = $this->getRequest();
        $arguments = $this->getArguments();
        $reception = new  Reception($request, $arguments);

        $method = $request->getMethod();
        $response = $this->getResponse();
        switch ($method) {
            case self::POST:
                $response = $this->create($reception);
                break;
        }

        return $response;
    }

    private function create(Reception $reception): Response
    {
        $item = $reception->toCreate();

        $userSet = (new UserManager($item, $this->getDataPath()))->create();

        $response = (new Presentation($this->getRequest(), $this->getResponse(), $userSet))->process();

        return $response;
    }
}
