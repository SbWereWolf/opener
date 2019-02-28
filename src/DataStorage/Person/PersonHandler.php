<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 21:49
 */

namespace DataStorage\Person;


use BusinessLogic\Person\IPerson;
use BusinessLogic\Person\Person;
use BusinessLogic\Session\Session;
use BusinessLogic\Session\SessionSet;
use DataStorage\Basis\DataHandler;
use DataStorage\Session\SessionAccess;
use DataStorage\Session\SessionAccessMysql;
use DataStorage\Session\SessionAccessSqlite;

class PersonHandler extends DataHandler
{
    private $personAccess = null;
    private $sessionAccess = null;

    public function registerPerson(IPerson $user): \BusinessLogic\Basis\Content
    {
        $result = $this->getPersonAccess()->insert($user)->getData();

        return $result;
    }

    public function startSession(IPerson $user): \BusinessLogic\Basis\Content
    {
        $result = new SessionSet();

        $this->begin();
        try {

            $storedUser = new Person();
            foreach ($this->getPersonAccess()->select($user)->getData()->next() as $dataElement) {
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

    private function getPersonAccess(): PersonAccess
    {
        $personAccess = $this->personAccess;
        $isExists = !empty($personAccess);

        if (!$isExists) {
            $access = $this->getAccess();

            switch (DBMS) {
                case SQLITE:
                    $personAccess = new PersonAccessSqlite($access);
                    break;
                case MYSQL:
                    $personAccess = new PersonAccessMysql($access);
                    break;
            }
            $this->personAccess = $personAccess;
        }

        return $personAccess;
    }

    private function getSessionAccess(): SessionAccess
    {
        $sessionAccess = $this->sessionAccess;
        $isExists = !empty($sessionAccess);

        if (!$isExists) {
            $access = $this->getAccess();

            switch (DBMS) {
                case SQLITE:
                    $sessionAccess = new SessionAccessSqlite($access);
                    break;
                case MYSQL:
                    $sessionAccess = new SessionAccessMysql($access);
                    break;
            }
            $this->sessionAccess = $sessionAccess;
        }

        return $sessionAccess;
    }
}
