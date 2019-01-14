<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 14.01.2019 23:52
 */

namespace Latch;


interface Token
{

    public function setToken(string $token);

    public function getToken(): string;
}
