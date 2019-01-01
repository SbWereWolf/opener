<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 18:09
 */

namespace Latch;


use Session\IToken;

class CheckInLogbook implements ICheckInLogbook
{

    public function getActual(): array
    {
        return array();
    }

    public function getCurrent(IToken $token): array
    {
        return array();
    }
}
