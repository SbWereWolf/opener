<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 5:17
 */

namespace BusinessLogic\User;


/**
 * @SWG\Definition(
 *   definition="user",
 *   type="object",
 *   description="user of latch",
 *   @SWG\Property(
 *       property="email",
 *       type="string",
 *   ),
 *   @SWG\Property(
 *       property="password",
 *       type="string",
 *   ),
 * )
 */
interface IUser
{
    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email): IUser;

    /**
     * @param string $secret
     * @return User
     */
    public function setSecret($secret): IUser;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getSecret(): string;

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): IUser;

    /**
     * @return string
     */
    public function getPassword(): string;
}
