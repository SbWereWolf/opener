<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 14.01.2019 23:52
 */

namespace BusinessLogic\Session;


/**
 * @SWG\Definition(
 *   definition="token",
 *   type="string",
 *   description="session of user",
 *   @SWG\Property(
 *       property="token",
 *       type="string",
 *   ),
 * )
 */
interface Token
{

    public function setToken(string $token);

    public function getToken(): string;
}
