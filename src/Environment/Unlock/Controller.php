<?php

namespace Environment\Unlock;


use BusinessLogic\Unlock\UnlockManager;
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

        $isGet = $request->isGet();
        $isDelete = $request->isDelete();
        $isPost = $request->isPost();

        $isValid = $isGet || $isPost || $isDelete;
        $reception = null;
        if ($isValid) {
            $arguments = $this->getArguments();
            $reception = new  Reception($request, $arguments);
        }

        $response = $this->getResponse();

        if ($isGet) {
            $response = $this->read($reception);
        }
        if ($isPost) {
            $response = $this->create($reception);
        }
        if ($isDelete) {
            $response = $this->delete($reception);
        }

        return $response;
    }

    private function read(Reception $reception): Response
    {
        $pattern = $reception->toRead();

        $dataSet = (new UnlockManager($pattern, $this->getDataPath()))->checkPoint();

        $presentation = (new Presentation($this->getRequest(), $this->getResponse(), $dataSet))
            ->letTransformFailToNotFound();
        $response = $presentation->process();

        return $response;
    }

    /**
     * @param Reception $reception
     * @return Response
     * @throws \Exception
     */
    private function create(Reception $reception): Response
    {
        $item = $reception->toCreate();

        $dataSet = (new UnlockManager($item, $this->getDataPath()))->scheduleUnlock();

        $response = (new Presentation($this->getRequest(), $this->getResponse(), $dataSet))->process();

        return $response;
    }

    /**
     * @param Reception $reception
     * @return Response
     * @throws \Exception
     */
    private function delete(Reception $reception): Response
    {
        $item = $reception->toDelete();

        $dataSet = (new UnlockManager($item, $this->getDataPath()))->confirmUnlock();

        $response = (new Presentation($this->getRequest(), $this->getResponse(), $dataSet))->process();

        return $response;
    }
}
