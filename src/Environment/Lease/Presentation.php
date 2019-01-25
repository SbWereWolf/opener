<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 03.01.2019 1:09
 */

namespace Environment\Lease;


use Environment\Basis\HttpCode;
use Environment\Basis\IPresentation;
use Slim\Http\Response;

class Presentation extends \Environment\Basis\Presentation
{
    /**
     * @return Response
     */
    public function process(): Response
    {
        $shouldAttach = $this->shouldAttach();
        if ($shouldAttach) {
            $this->attachContent();
        }

        $response = (new HttpCode($this->getResponse(), $this->getRequest()))->process($this->isSuccess());

        return $response;
    }

    /**
     * @return IPresentation
     */
    private function attachContent(): IPresentation
    {
        $asArray = (new LeaseSetView($this->getContent()))->toArray();
        $response = $this->getResponse()->withJson($asArray);
        $this->setResponse($response);
        return $this;
    }
}
