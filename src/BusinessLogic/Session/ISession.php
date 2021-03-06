<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 5:17
 */

namespace BusinessLogic\Session;


/**
 * @SWG\Definition(
 *   definition="session",
 *   type="object",
 *   description="session of user",
 *   @SWG\Property(
 *       property="token",
 *       type="string",
 *   ),
 *   @SWG\Property(
 *       property="finish",
 *       type="integer",
 *   ),
 * )
 */
interface ISession extends Token
{
    public function setToken(string $token): self;

    public function getToken(): string;

    public function setFinish(int $finish): self;

    public function getFinish(): int;

    public function setEmail(string $email): self;

    public function getEmail(): string;
}
