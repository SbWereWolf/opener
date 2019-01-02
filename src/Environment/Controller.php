<?php

namespace Environment;

use Slim\Http\Request;
use Slim\Http\Response;
use Environment\IController;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Controller implements IController
{
    const GET = 'get';
    const POST = 'post';
    const PUT = 'put';
    const DELETE = 'delete';

    private $request = null;
    private $response = null;
    private $arguments = array();
    private $method = '';
    private $dataPath = '';

    function __construct(Request $request, Response $response, array $parametersInPath, string $method, string $dataPath)
    {
        $this->setArguments($parametersInPath)
            ->setDataPath($dataPath)
            ->setMethod($method)
            ->setRequest($request)
            ->setResponse($response);
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function process(): Response
    {
        throw new \Exception('Method process() Not Implemented');
    }

    /**
     * @param Response $response
     * @return Controller
     */
    protected function setResponse(Response $response): Controller
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return Response
     */
    protected function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param array $arguments
     * @return Controller
     */
    protected function setArguments(array $arguments): Controller
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @return array
     */
    protected function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param string $method
     * @return Controller
     */
    protected function setMethod(string $method): Controller
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string
     */
    protected function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $dataPath
     * @return Controller
     */
    protected function setDataPath(string $dataPath): Controller
    {
        $this->dataPath = $dataPath;
        return $this;
    }

    /**
     * @return string
     */
    protected function getDataPath(): string
    {
        return $this->dataPath;
    }

    /**
     * @return Request
     */
    protected function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     * @return Controller
     */
    protected function setRequest(Request $request): Controller
    {
        $this->request = $request;
        return $this;
    }
}
