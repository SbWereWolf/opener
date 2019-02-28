<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 5:17
 */

namespace BusinessLogic\Person;


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
interface IPerson
{
    /**
     * @param string $email
     * @return Person
     */
    public function setEmail($email): IPerson;

    /**
     * @param string $secret
     * @return Person
     */
    public function setSecret($secret): IPerson;

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
     * @return Person
     */
    public function setPassword(string $password): IPerson;

    /**
     * @return string
     */
    public function getPassword(): string;
}
