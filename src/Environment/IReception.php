<?php

namespace Environment;


/**
 * city-call
 * Copyright © 2018 Volkhin Nikolay
 * 25.06.18 21:53
 */
interface IReception
{
    public function toCreate();

    public function toRead();

    public function toDelete();

    public function toUpdate();
}
