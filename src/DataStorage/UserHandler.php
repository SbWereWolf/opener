<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 21:49
 */

namespace DataStorage;


use Latch\Content;
use Latch\IUser;
use Latch\Session;
use Latch\SessionSet;
use Latch\User;

class UserHandler extends DataHandler
{
    private $userAccess = null;
    private $sessionAccess = null;

    public function registerUser(IUser $user): Content
    {
        $result = $this->getUserAccess()->insert($user)->getData();

        return $result;
    }

    public function startSession(IUser $user): Content
    {
        $result = new SessionSet();

        $this->begin();
        try {

            $storedUser = new User();
            foreach ($this->getUserAccess()->select($user)->getData()->next() as $dataElement){
                $storedUser = $dataElement;
                break;
            }

            $secret = $storedUser->getSecret();
            $password = $user->getPassword();

            $isValid = password_verify($password, $secret);

            if ($isValid) {
                $token = password_hash($secret, PASSWORD_DEFAULT);
                $token = str_replace(' ', '-', $token); // Replaces all spaces with hyphens.
                $token = preg_replace('/[^A-Za-z0-9\-]/', '', $token); // Removes special chars.
                /* TODO: Check token for uniqueness against current opened sessions */
                $email = $user->getEmail();

                $session = (new Session())->setToken($token)->setEmail($email);

                $result = $this->getSessionAccess()->insertWithEmail($session)->getData();

                $result->push($session);

                $this->commit();
            }

            if (!$isValid) {
                $this->rollBack();
            }
        } catch (\Exception $e) {
            $this->rollBack();
        }

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

    private function getSessionAccess(): SessionAccess
    {
        $sessionAccess = $this->sessionAccess;
        $isExists = !empty($sessionAccess);

        if (!$isExists) {
            $access = $this->getAccess();
            $sessionAccess = new SessionAccess($access);
            $this->sessionAccess = $sessionAccess;
        }

        return $sessionAccess;
    }
}
