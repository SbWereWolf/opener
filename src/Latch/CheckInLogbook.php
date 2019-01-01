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

    public function getActual(): IHaspSet
    {
        $result = new HaspSet();
        return $result;
    }

    public function getCurrent(IToken $token): IHaspSet
    {
        $result = new HaspSet();
        return $result;
    }
}
