<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 1:45
 */

namespace Latch;


interface ILeaseSet
{

    public function push(ILease $element): bool;

    /**
     * @return \Latch\ILease
     */
    public function next();

    public function setSuccessStatus();

    public function setFailStatus();

    public function isSuccess();
}
