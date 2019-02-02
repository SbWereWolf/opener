<?php

namespace Environment\Session;


use BusinessLogic\Session\ISession;
use BusinessLogic\Session\SessionManager;
use Slim\Http\Response;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Controller extends \Environment\Basis\Controller
{
    public function process(): Response
    {
        $request = $this->getRequest();
        $isDelete = $request->isDelete();

        $response = $this->getResponse();
        if ($isDelete) {

            $arguments = $this->getArguments();
            $reception = new  Reception($request, $arguments);

            $response = $this->delete($reception);
        }

        return $response;
    }

    /**
     * @param Reception $reception
     * @return Response
     * @throws \Exception
     */
    private function delete(Reception $reception): Response
    {
        /** @var ISession $item */
        $item = $reception->toDelete();

        $content = (new SessionManager($item, $this->getDataPath()))->finish();

        $response = (new Presentation($this->getRequest(), $this->getResponse(), $content))->process();

        return $response;
    }
}
