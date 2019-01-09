<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 5:17
 */

namespace Latch;


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
 *       property="secret",
 *       type="string",
 *   ),
 * )
 * @SWG\Definition(
 *   definition="user-with-id",
 *   type="object",
 *   description="user of latch with id",
 *   @SWG\Property(
 *       property="id",
 *       type="integer",
 *   ),
 *   @SWG\Property(
 *       property="email",
 *       type="string",
 *   ),
 *   @SWG\Property(
 *       property="secret",
 *       type="string",
 *   ),
 * )
 */
interface IUser
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
    public function setId($id): IUser;

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
