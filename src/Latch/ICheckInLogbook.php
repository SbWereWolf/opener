<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 18:09
 */
namespace Latch;


use Session\IToken;

interface ICheckInLogbook
{
    public function getActual():array;
    public function getCurrent(IToken $token):array;
}
