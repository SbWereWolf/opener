<?php

namespace Environment\Lease;


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

        $method = $request->getMethod();
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
     */
    private function read(Reception $reception): Response
    {
        /** @var \Latch\Lease $pattern */
        $pattern = $reception->toRead();

        $leaseSet = (new LeaseManager($pattern, $this->getDataPath()))->read();

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
        /** @var \Latch\Lease $item */
        $item = $reception->toCreate();

        $leaseSet = (new LeaseManager($item, $this->getDataPath()))->create();

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

        (new LeaseManager($item, $this->getDataPath()))->update();

        $response = (new Presentation($this->getRequest(), $this->getResponse(), $leaseSet))->process();

        return $response;
    }
}
