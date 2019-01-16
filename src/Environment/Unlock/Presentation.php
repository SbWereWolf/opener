<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 03.01.2019 1:09
 */

namespace Environment\Unlock;


use Environment\HttpCode;
use Slim\Http\Response;

class Presentation extends \Environment\Presentation
{

    private $shouldTransformFailToNotFound = false;

    public function letTransformFailToNotFound(): self
    {
        $this->shouldTransformFailToNotFound = true;
        return $this;
    }

    /**
     * @return Response
     */
    public function process(): Response
    {
        $httpCode = new HttpCode($this->getResponse(), $this->getRequest());

        $shouldTransformFailToNoData = $this->isTransformFailToNotFound();
        if ($shouldTransformFailToNoData) {
            $httpCode->letTransformFailToNotFound();
        }

        $isSuccess = $this->isSuccess();
        $response = $httpCode->process($isSuccess);

        return $response;
    }

    private function isTransformFailToNotFound(): bool
    {
        return $this->shouldTransformFailToNotFound;
    }
}
