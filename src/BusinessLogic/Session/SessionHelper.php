<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 15.01.2019 2:04
 */

namespace BusinessLogic\Session;


use DataStorage\Basis\DataSource;

class SessionHelper
{
    private $dataPath = '';

    public function __construct(DataSource $dataPath)
    {
        $this->setDataPath($dataPath);
    }

    public function prolong(string $token): bool
    {
        $isValid = !empty($token);

        $result = false;
        if ($isValid) {
            $session = (new Session())->setToken($token);
            $result = (new SessionManager($session, $this->getDataPath()))->prolong();
        }

        return $result;
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
