<?php

namespace Environment\User;


use BusinessLogic\Person\PersonManager;
use Environment\Session;
use Slim\Http\Response;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Controller extends \Environment\Basis\Controller
{
    const LOG_IN = 'login/';

    public function process(): Response
    {
        $request = $this->getRequest();
        $isPost = $request->isPost();

        $reception = null;
        $shouldProcess = false;
        $shouldLogIn = false;
        if ($isPost) {
            $shouldLogIn = $this->isLogIn();

            $arguments = $this->getArguments();
            $reception = new  Reception($request, $arguments);

            $shouldProcess = true;
        }

        $response = $this->getResponse();
        if ($shouldProcess && $shouldLogIn) {
            $response = $this->logIn($reception);
        }
        if ($shouldProcess && !$shouldLogIn) {
            $response = $this->create($reception);
        }

        return $response;
    }

    private function create(Reception $reception): Response
    {
        $item = $reception->toCreate();

        $userSet = (new PersonManager($item, $this->getDataPath()))->create();

        $response = (new Presentation($this->getRequest(), $this->getResponse(), $userSet))->process();

        return $response;
    }

    private function logIn(Reception $reception): Response
    {
        $item = $reception->toCreate();

        $sessionSet = (new PersonManager($item, $this->getDataPath()))->logIn();

        $response = (new Session\Presentation($this->getRequest(), $this->getResponse(), $sessionSet))
            ->process();

        return $response;
    }

    /**
     * @return bool
     */
    private function isLogIn(): bool
    {
        $path = $this->getRequest()->getUri()->getPath();
        $shouldStartSession = boolval(strpos($path, self::LOG_IN));

        return $shouldStartSession;
    }
}
