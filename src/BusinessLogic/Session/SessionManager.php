<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 18:47
 */

namespace BusinessLogic\Session;


use BusinessLogic\Basis\Content;
use DataStorage\Basis\DataSource;
use DataStorage\Session\SessionHandler;

class SessionManager
{
    private $session = null;
    private $dataPath = null;

    public function __construct(ISession $session, DataSource $dataPath)
    {
        $this->setDataPath($dataPath)
            ->setSession($session);
    }

    public function finish(): Content
    {
        $session = $this->getSession();

        $dataPath = $this->getDataPath();
        $result = (new SessionHandler($dataPath))->finish($session);

        return $result;
    }

    public function prolong(): bool
    {
        $session = $this->getSession();
        $dataPath = $this->getDataPath();

        $result = (new SessionHandler($dataPath))->prolong($session);

        $isValid = $result->isSuccess();

        return $isValid;
    }

    public function getSession(): ISession
    {
        return $this->session;
    }

    public function setSession(ISession $session): self
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataPath(): DataSource
    {
        return $this->dataPath;
    }

    public function setDataPath(DataSource $dataPath): self
    {
        $this->dataPath = $dataPath;
        return $this;
    }
}
