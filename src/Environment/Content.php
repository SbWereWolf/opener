<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 03.01.2019 1:09
 */

namespace Environment;


use Latch\ILeaseSet;
use Presentation\LeaseSetView;
use Slim\Http\Response;

class Content implements \Environment\Output
{
    private $content = null;
    /** @var Response  $response */
    private $response = null;

    public function __construct(Response $response, ILeaseSet $content)
    {
        $this->setContent($content)->response = $response;
    }

    /**
     * @param array $content
     * @return Content
     */
    private function setContent(ILeaseSet $content): \Environment\Output
    {
        $this->content = $content;
        return $this;
    }

    public function isSuccess()
    {
        return $this->getContent()->isSuccess();
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return Output
     * @throws \Exception
     */
    public function attachContent(): \Environment\Output
    {
        throw new \Exception('Method attachContent() Not Implemented');
    }

    /**
     * @return array
     */
    protected function getContent(): ILeaseSet
    {
        return $this->content;
    }

    /**
     * @param Response $response
     * @return Content
     */
    protected function setResponse(Response $response): \Environment\Output
    {
        $this->response = $response;
        return $this;
    }
}
