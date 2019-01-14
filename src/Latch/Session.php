<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 5:17
 */

namespace Latch;


class Session implements ISession
{
    private $token = '';
    private $finish = 0;
    private $email = '';

    /**
     * @return int
     */
    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): ISession
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getFinish(): int
    {
        return $this->finish;
    }

    public function setFinish(int $finish): ISession
    {
        $this->finish = $finish;
        return $this;

    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Session
     */
    public function setEmail(string $email): ISession
    {
        $this->email = $email;
        return $this;
    }
}
