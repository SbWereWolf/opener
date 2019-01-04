<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 03.01.2019 1:09
 */

namespace Environment\Lease;


use Presentation\LeaseSetView;

class Content extends \Environment\Content
{
    public function attachContent(): \Environment\Output
    {
        $json = (new LeaseSetView($this->getContent()))->toJson();
        $response = $this->getResponse()->withJson($json);
        $this->setResponse($response);
        return $this;
    }
}
