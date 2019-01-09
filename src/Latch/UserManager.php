<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 18:47
 */

namespace Latch;


use DataStorage\UserHandler;

class UserManager
{
    private $user = null;
    private $dataPath = '';

    public function __construct(IUser $user, string $dataPath)
    {
        $this->setDataPath($dataPath)
            ->setUser($user);
    }

    public function create(): Content
    {

        $lease = $this->getUser();

        $dataPath = $this->getDataPath();
        $result = (new UserHandler($dataPath))->registerUser($lease);

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
    public function getDataPath(): string
    {
        return $this->dataPath;
    }

    /**
     * @param string $dataPath
     * @return LeaseManager
     */
    public function setDataPath(string $dataPath): self
    {
        $this->dataPath = $dataPath;
        return $this;
    }
}
