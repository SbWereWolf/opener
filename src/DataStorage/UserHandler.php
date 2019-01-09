<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 21:49
 */

namespace DataStorage;


use Latch\Content;
use Latch\IUser;

class UserHandler extends DataHandler
{
    private $userAccess = null;

    public function registerUser(IUser $lease): Content
    {
        $result = $this->getUserAccess()->insert($lease)->getData();

        return $result;
    }

    private function getUserAccess(): UserAccess
    {
        $userAccess = $this->userAccess;
        $isExists = !empty($userAccess);

        if (!$isExists) {
            $access = $this->getAccess();
            $userAccess = new UserAccess($access);
            $this->userAccess = $userAccess;
        }

        return $userAccess;
    }
}
