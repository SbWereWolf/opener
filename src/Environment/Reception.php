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
        throw new \Exception('Method toCreate() Not Implemented');
    }

    /**
     * @throws \Exception
     */
    public function toRead()
    {
        throw new \Exception('Method toRead() Not Implemented');
    }

    /**
     * @throws \Exception
     */
    public function toDelete()
    {
        throw new \Exception('Method toDelete() Not Implemented');
    }

    /**
     * @throws \Exception
     */
    public function toUpdate()
    {
        throw new \Exception('Method toUpdate() Not Implemented');
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
