<?php

namespace Environment\Unlock;


use Slim\Http\Response;
use Environment\IController;

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

        $method = $this->getMethod();
        $response = $this->getResponse();
        switch ($method) {
            case self::DELETE:
                $response = $this->delete($reception);
                break;
            case self::GET:
                $response = $this->read($reception);
                break;
            case self::POST:
                $response = $this->create($reception);
                break;
            case self::PUT:
                $response = $this->update($reception);
                break;
        }

        return $response;
    }

    private function delete(Reception $reception): Response
    {
        $item = $reception->toDelete();

        $logicResult = (new Logic($item, $this->dataPath))->delete();

        $response = (new Presentation($this->response, $logicResult))->fromDelete();

        return $response;
    }

    private function read(Reception $reception): Response
    {
        $item = $reception->toRead();

        $logicResult = (new Logic($item, $this->dataPath))->read();

        $response = (new Presentation($this->response, $logicResult))->fromRead();

        return $response;
    }

    private function create(Reception $reception): Response
    {
        $item = $reception->toCreate();

        $logicResult = (new Logic($item, $this->dataPath))->create();

        $response = (new Presentation($this->response, $logicResult))->fromCreate();

        return $response;
    }

    private function update(Reception $reception): Response
    {
        $item = $reception->toUpdate();

        $logicResult = (new Logic($item, $this->dataPath))->update();

        $response = (new Presentation($this->response, $logicResult))->fromUpdate();

        return $response;
    }
}
