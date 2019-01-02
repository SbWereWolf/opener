<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 03.01.2019 1:09
 */

namespace Environment\Lease;


use Latch\ILeaseSet;
use Presentation\LeaseSetView;
use Slim\Http\Response;

class Output implements \Environment\Output
{
    private $dataSet = null;
    /** @var Response  $response */
    private $response = null;

    public function __construct(Response $response, ILeaseSet $data)
    {
        $this->setData($data)->response = $response;
    }

    /**
     * @param array $data
     * @return Output
     */
    private function setData(ILeaseSet $data): Output
    {
        $this->dataSet = $data;
        return $this;
    }

    public function isSuccess()
    {
        return $this->getData()->isSuccess();
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function setResponse():Output
    {
        $json = (new LeaseSetView($this->getData()))->toJson();
        $this->response->withJson($json);
        return $this;
    }

    /**
     * @return array
     */
    private function getData(): ILeaseSet
    {
        return $this->dataSet;
    }
}
