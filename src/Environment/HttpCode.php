<?php

namespace Environment;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:52
 */
class HttpCode implements IHttpCode
{

    private $response = null;
    private $request = null;

    function __construct(Response $response, Request $request)
    {
        $this->setRequest($request)
            ->setResponse($response);
    }

    /**
     * @param Response $response
     * @return HttpCode
     */
    private function setResponse(Response $response): HttpCode
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @param Request $request
     * @return HttpCode
     */
    private function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function process(bool $isSuccess): Response
    {
        $method = $this->getRequest()->getMethod();
        $status = $this->calculateStatus($method, $isSuccess);
        $response = $this->getResponse()->withStatus($status);

        return $response;
    }

    /**
     * @return Request
     */
    private function getRequest(): Request
    {
        return $this->request;
    }

    private function calculateStatus(string $method, bool $isSuccess): int
    {
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
    private function getResponse(): Response
    {
        return $this->response;
    }

}
