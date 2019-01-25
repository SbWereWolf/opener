<?php

namespace Environment\Lease;


use BusinessLogic\Lease\ILease;
use BusinessLogic\Lease\LeaseProcess;
use Slim\Http\Response;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Controller extends \Environment\Basis\Controller
{
    private $shouldRetrieveActual = false;
    private $shouldRetrieveCurrent = false;

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
        $item = $reception->toRead();

        $token = $item->getToken();
        $dataPath = $this->getDataPath();
        $leaseSet = (new LeaseProcess($item))->retrieveActual($dataPath, $token);

        $response = (new Presentation($this->getRequest(), $this->getResponse(), $leaseSet))->process();

        return $response;
    }

    private function retrieveCurrent(Reception $reception): Response
    {
        $item = $reception->toRead();

        $token = $item->getToken();
        $dataPath = $this->getDataPath();

        $leaseSet = (new LeaseProcess($item))->retrieveCurrent($dataPath, $token);

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
        $leaseSet = (new LeaseProcess($item))->addNewLease($dataPath, $token);

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
        /** @var ILease $item */
        $item = $reception->toUpdate();

        $leaseSet = (new LeaseProcess($item))->overwriteLease($this->getDataPath());

        $response = (new Presentation($this->getRequest(), $this->getResponse(), $leaseSet))->process();

        return $response;
    }

    public function letRetrieveActual(): self
    {
        $this->shouldRetrieveActual = true;

        return $this;
    }

    public function letRetrieveCurrent(): self
    {
        $this->shouldRetrieveCurrent = true;

        return $this;
    }

    /**
     * @return bool
     */
    private function isRetrieveActual(): bool
    {
        return $this->shouldRetrieveActual;
    }

    /**
     * @return bool
     */
    private function isRetrieveCurrent(): bool
    {
        return $this->shouldRetrieveCurrent;
    }
}
