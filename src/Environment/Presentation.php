<?php

namespace Environment;

use Slim\Http\Response;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:52
 */
class Presentation implements IPresentation
{

    private $response = null;
    private $output = null;

    function __construct(Response $response, Output $output)
    {
        $this->setOutput($output)
            ->setResponse($response);
    }

    public function fromCreate(): Response
    {
        $response = $this->setupByMethod(self::POST);

        return $response;
    }

    private function setupByMethod(string $method): Response
    {
        $response = $this->getOutput()->getResponse();
        $statusCode = $this->getStatusCode($method);
        $response = $response->withStatus($statusCode);

        return $response;
    }

    /**
     * @return null
     */
    protected function getOutput(): Output
    {
        return $this->output;
    }

    /**
     * @param null $output
     * @return Presentation
     */
    protected function setOutput(Output $output)
    {
        $this->output = $output;
        return $this;
    }

    private function getStatusCode(string $method): int
    {
        $isSuccess = $this->getOutput()->isSuccess();
        if (!$isSuccess) {
            $statusCode = self::ERROR;
        }
        if ($isSuccess) {
            switch ($method) {
                case self::POST :
                    $statusCode = self::POST_OK;
                    break;
                case self::GET:
                    $statusCode = self::COMMON_OK;
                    break;
                case self::PUT:
                    $statusCode = self::COMMON_OK;
                    break;
                case self::DELETE:
                    $statusCode = self::DELETE_OK;
                    break;
            }
        }
        return $statusCode;
    }

    /**
     * @return Response
     */
    protected function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param Response $response
     * @return Presentation
     */
    protected function setResponse(Response $response): Presentation
    {
        $this->response = $response;
        return $this;
    }

    public function fromRead(): Response
    {
        $response = $this->setupByMethod(self::GET);

        return $response;
    }

    public function fromDelete(): Response
    {
        $response = $this->setupByMethod(self::DELETE);

        return $response;
    }

    public function fromUpdate(): Response
    {
        $response = $this->setupByMethod(self::PUT);

        return $response;
    }
}
