<?php

namespace Environment\Lease;


use Environment\Presentation;
use Latch\LeaseManager;
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

        $method = $this->getMethod();
        $response = $this->getResponse();
        switch ($method) {
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

    /**
     * @param Reception $reception
     * @return Response
     * @throws \Exception
     */
    private function read(Reception $reception): Response
    {
        /** @var \Latch\Lease $item */
        $item = $reception->toRead();

        $dataPath = $this->getDataPath();
        $leaseSet = (new LeaseManager($item, $dataPath))->read();

        $output = (new Output($this->getResponse(), $leaseSet))->setResponse();
        $response = (new Presentation($this->getResponse(), $output))->fromRead();

        return $response;
    }

    /**
     * @param Reception $reception
     * @return Response
     * @throws \Exception
     */
    private function create(Reception $reception): Response
    {
        /** @var \Latch\Lease $item */
        $item = $reception->toCreate();

        $dataPath = $this->getDataPath();
        $leaseSet = (new LeaseManager($item, $dataPath))->create();

        $output = (new Output($this->getResponse(), $leaseSet))->setResponse();
        $response = (new Presentation($this->getResponse(), $output))->fromCreate();

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

        $dataPath = $this->getDataPath();
        $leaseSet = (new LeaseManager($item, $dataPath))->update();

        $output = (new Output($this->getResponse(), $leaseSet))->setResponse();
        $response = (new Presentation($this->getResponse(), $output))->fromUpdate();

        return $response;
    }
}
