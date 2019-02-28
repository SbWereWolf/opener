<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 5:17
 */

namespace BusinessLogic\Person;


class Person implements IPerson
{

    private $email = '';
    private $secret = '';
    private $password = '';

    public function setEmail($email): IPerson
    {
        $this->email = strval($email);
        return $this;
    }

    public function setSecret($secret): IPerson
    {
        $this->secret = strval($secret);
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
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $password
     * @return Person
     */
    public function setPassword(string $password): IPerson
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
