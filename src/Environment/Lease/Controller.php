<?php

namespace Environment\Lease;


use Latch\LeaseManager;
use Latch\LeaseSet;
use Slim\Http\Response;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Controller extends \Environment\Controller
{
    private $letRetrieveActual = false;
    private $letRetrieveCurrent = false;

    public function process(): Response
    {
        $request = $this->getRequest();

        $isGet = $request->isGet();
        $isPut = $request->isPut();
        $isPost = $request->isPost();

        $isValid = $isGet || $isPost || $isPut;
        $reception = null;
        if ($isValid) {
            $arguments = $this->getArguments();
            $reception = new  Reception($request, $arguments);
        }

        $response = $this->getResponse();

        $letRetrieveActual = $isGet && $this->isRetrieveActual();
        if ($letRetrieveActual) {
            $response = $this->retrieveActual($reception);
        }

        $letRetrieveCurrent = $isGet && $this->isRetrieveCurrent();
        if ($letRetrieveCurrent) {
            $response = $this->retrieveCurrent($reception);
        }
        if ($isPost) {
            $response = $this->create($reception);
        }
        if ($isPut) {
            $response = $this->update($reception);
        }

        return $response;
    }

    private function retrieveActual(Reception $reception): Response
    {
        $pattern = $reception->toRead();

        $token = $pattern->getToken();
        $dataPath = $this->getDataPath();
        $isSuccess = $this->prolongSession($token, $dataPath);

        $leaseSet = (new LeaseManager($pattern, $dataPath))->retrieveActual();

        $response = (new Presentation($this->getRequest(), $this->getResponse(), $leaseSet))->process();

        return $response;
    }

    private function retrieveCurrent(Reception $reception): Response
    {
        $pattern = $reception->toRead();

        $token = $pattern->getToken();
        $dataPath = $this->getDataPath();
        $isSuccess = $this->prolongSession($token, $dataPath);

        $leaseSet = new LeaseSet();
        if ($isSuccess) {
            $leaseSet = (new LeaseManager($pattern, $this->getDataPath()))->retrieveCurrent();
        }

        $response = (new Presentation($this->getRequest(), $this->getResponse(), $leaseSet))->process();

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

        $token = $item->getToken();
        $dataPath = $this->getDataPath();
        $isSuccess = $this->prolongSession($token, $dataPath);

        $leaseSet = new LeaseSet();
        if ($isSuccess) {
            $leaseSet = (new LeaseManager($item, $this->getDataPath()))->create();
        }

        $response = (new Presentation($this->getRequest(), $this->getResponse(), $leaseSet))->process();

        return $response;
    }

    /**
     * @param Reception $reception
     * @return Response
     * @throws \Exception
     */
    private function update(Reception $reception): Response
    {
        /** @var \Latch\Lease $item */
        $item = $reception->toUpdate();

        $leaseSet = (new LeaseManager($item, $this->getDataPath()))->update();

        $response = (new Presentation($this->getRequest(), $this->getResponse(), $leaseSet))->process();

        return $response;
    }

    public function letRetrieveActual(): self
    {
        $this->letRetrieveActual = true;

        return $this;
    }

    public function letRetrieveCurrent(): self
    {
        $this->letRetrieveCurrent = true;

        return $this;
    }

    /**
     * @return bool
     */
    private function isRetrieveActual(): bool
    {
        return $this->letRetrieveActual;
    }

    /**
     * @return bool
     */
    private function isRetrieveCurrent(): bool
    {
        return $this->letRetrieveCurrent;
    }
}
