<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 5:17
 */

namespace DataStorage;


interface IUser extends Table
{
    const PARAM_ID = ':ID';
    const PARAM_EMAIL = ':EMAIL';
    const PARAM_SECRET = ':SECRET';

    const COLUMN_ID = 'id';
    const COLUMN_EMAIL = 'email';
    const COLUMN_SECRET = 'secret';

    /**
     * @param int $id
     * @return User
     */
    public function setId($id): User;

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email): User;

    /**
     * @param string $secret
     * @return User
     */
    public function setSecret($secret): User;

    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getSecret(): string;


}
