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
        $asArray = (new LeaseSetView($this->getContent()))->toArray();
        $response = $this->getResponse()->withJson($asArray);
        $this->setResponse($response);
        return $this;
    }
}
