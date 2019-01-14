<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 18:47
 */

namespace Latch;


use DataStorage\SessionHandler;

class SessionManager
{
    private $session = null;
    private $dataPath = '';

    public function __construct(ISession $session, string $dataPath)
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
        if ($isValid) {
            $isValid = !empty($result->next());
        }

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
    public function getDataPath(): string
    {
        return $this->dataPath;
    }

    public function setDataPath(string $dataPath): self
    {
        $this->dataPath = $dataPath;
        return $this;
    }
}
