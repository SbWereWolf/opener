<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 5:17
 */

namespace DataStorage;


class User implements IUser
{

    private $id = 0;
    private $email = '';
    private $secret = '';
    private $table = "'user'";

    /**
     * @param int $id
     * @return User
     */
    public function setId($id): User
    {
        $this->id = intval($id);
        return $this;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email): User
    {
        $this->email = strval($email);
        return $this;
    }

    /**
     * @param string $secret
     * @return User
     */
    public function setSecret($secret): User
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

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }


}
