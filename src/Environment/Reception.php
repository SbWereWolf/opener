<?php

namespace Environment;


use LanguageFeatures\ArrayParser;
use Slim\Http\Request;

/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
class Reception implements IReception
{
    protected $request = null;
    protected $arguments = array();
    protected $parser = null;

    function __construct(Request $request, array $arguments)
    {
        $this->setArguments($arguments)
            ->setRequest($request);
    }

    /**
     * @throws \Exception
     */
    public function toCreate()
    {
        $item = $this->setupFromBody();

        return $item;
    }

    /**
     * @throws \Exception
     */
    public function toRead()
    {
        $item = $this->setupFromPath();

        return $item;
    }

    /**
     * @throws \Exception
     */
    public function toDelete()
    {
        $item = $this->setupFromPath();

        return $item;
    }

    /**
     * @throws \Exception
     */
    public function toUpdate()
    {
        $item = $this->setupFromBody();

        return $item;
    }

    /**
     * @throws \Exception
     */
    private function setupFromBody()
    {
        throw new \Exception('Method setupFromBody() Not Implemented');
    }

    /**
     * @throws \Exception
     */
    private function setupFromPath()
    {
        throw new \Exception('Method setupFromPath() Not Implemented');
    }

    /**
     * @param null $parser
     * @return Reception
     */
    protected function setParser(ArrayParser $parser)
    {
        $this->parser = $parser;
        return $this;
    }

    /**
     * @return null
     */
    protected function getParser():ArrayParser
    {
        return $this->parser;
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
     * @return Reception
     */
    protected function setRequest(Request $request): Reception
    {
        $this->request = $request;
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
     * @param array $arguments
     * @return Reception
     */
    protected function setArguments(array $arguments): Reception
    {
        $this->arguments = $arguments;
        return $this;
    }
}
