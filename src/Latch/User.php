<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 5:17
 */

namespace Latch;


class User implements IUser
{

    private $id = 0;
    private $email = '';
    private $secret = '';

    public function setId($id): IUser
    {
        $this->id = intval($id);
        return $this;
    }

    public function setEmail($email): IUser
    {
        $this->email = strval($email);
        return $this;
    }

    public function setSecret($secret): IUser
    {
        $this->secret = strval($secret);
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }
}
