<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 21:49
 */

namespace DataStorage\User;


use BusinessLogic\Session\Session;
use BusinessLogic\Session\SessionSet;
use BusinessLogic\User\IUser;
use BusinessLogic\User\User;
use DataStorage\Basis\DataHandler;
use DataStorage\Session\SessionAccess;

class UserHandler extends DataHandler
{
    private $userAccess = null;
    private $sessionAccess = null;

    public function registerUser(IUser $user): \BusinessLogic\Basis\Content
    {
        $result = $this->getUserAccess()->insert($user)->getData();

        return $result;
    }

    public function startSession(IUser $user): \BusinessLogic\Basis\Content
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
                @session_start();
                $token = session_id();

                $isEmpty = empty($token);
                if ($isEmpty) {
                    $token = session_create_id();
                    @session_id($token);
                }

                session_write_close();
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
