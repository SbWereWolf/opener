<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 18:14
 */

namespace Session;


class Token implements IToken
{
    private $token = '';

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
