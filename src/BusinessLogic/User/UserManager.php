<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 18:47
 */

namespace BusinessLogic\User;


use BusinessLogic\Basis\Content;
use DataStorage\Basis\DataSource;
use DataStorage\User\UserHandler;

class UserManager
{
    private $user = null;
    private $dataPath = null;

    public function __construct(IUser $user, DataSource $dataPath)
    {
        $this->setDataPath($dataPath)
            ->setUser($user);
    }

    public function create(): Content
    {
        $user = $this->getUser();

        $password = $user->getPassword();
        $secret = password_hash($password, PASSWORD_BCRYPT);

        $user->setSecret($secret);

        $dataPath = $this->getDataPath();
        $result = (new UserHandler($dataPath))->registerUser($user);

        return $result;
    }

    public function logIn(): Content
    {
        $user = $this->getUser();

        $dataPath = $this->getDataPath();
        $result = (new UserHandler($dataPath))->startSession($user);

        return $result;
    }

    public function getUser(): IUser
    {
        return $this->user;
    }

    public function setUser($user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataPath(): DataSource
    {
        return $this->dataPath;
    }

    /**
     * @param string $dataPath
     * @return self
     */
    public function setDataPath(DataSource $dataPath): self
    {
        $this->dataPath = $dataPath;
        return $this;
    }
}
