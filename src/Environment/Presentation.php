<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 03.01.2019 1:09
 */

namespace Environment;


use Latch\Content;
use Slim\Http\Request;
use Slim\Http\Response;

class Presentation implements \Environment\IPresentation
{
    /** @var Content $content */
    private $content = null;
    /** @var Response $response */
    private $response = null;
    /** @var Request $request */
    private $request = null;

    public function __construct(Request $request, Response $response, Content $content)
    {
        $this->setContent($content)
            ->setResponse($response)
            ->setRequest($request);
    }

    /**
     * @param array $content
     * @return Presentation
     */
    private function setContent(Content $content): IPresentation
    {
        $this->content = $content;
        return $this;
    }

    protected function isSuccess(): bool
    {
        return $this->getContent()->isSuccess();
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return IPresentation
     * @throws \Exception
     */
    public function process(): Response
    {
        throw new \Exception('Method process() Not Implemented');
    }

    /**
     * @return bool
     */
    protected function shouldAttach(): bool
    {
        $request = $this->getRequest();
        $shouldAttach = $request->isGet() || $request->isPost();
        return $shouldAttach;
    }

    /**
     * @return array
     */
    protected function getContent(): Content
    {
        return $this->content;
    }

    /**
     * @param Response $response
     * @return Presentation
     */
    protected function setResponse(Response $response): IPresentation
    {
        $this->response = $response;
        return $this;
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
     * @return Presentation
     */
    private function setRequest(Request $request): IPresentation
    {
        $this->request = $request;
        return $this;
    }
}
