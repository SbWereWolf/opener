<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 1:45
 */

namespace Latch;


interface IHaspSet
{

    public function push(IHasp $element): bool;

    /**
     * @return \Latch\IHasp
     */
    public function next();
}
