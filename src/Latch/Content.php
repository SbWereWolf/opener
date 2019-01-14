<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 1:45
 */

namespace Latch;


interface Content
{

    public function push($element): bool;

    public function next();

    public function setSuccessStatus();

    public function setFailStatus();

    public function isSuccess(): bool;
}
