<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 16.01.2019 5:18
 */

namespace BusinessLogic\Unlock;


use BusinessLogic\Basis\Content;
use DataStorage\Basis\DataSource;
use DataStorage\Unlock\UnlockHandler;

class UnlockManager
{
    private $unlock = null;
    private $dataPath = null;

    public function __construct(IUnlock $unlock, DataSource $dataPath)
    {
        $this->setDataPath($dataPath)
            ->setUnlock($unlock);
    }

    public function checkPoint(): Content
    {
        $unlock = $this->getUnlock();
        $dataPath = $this->getDataPath();

        $result = (new UnlockHandler($dataPath))->read($unlock);

        return $result;
    }

    public function getUnlock(): IUnlock
    {
        return $this->unlock;
    }

    /**
     * @param IUnlock $unlock
     * @return UnlockManager
     */
    public function setUnlock(IUnlock $unlock): self
    {
        $this->unlock = $unlock;
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
     * @return UnlockManager
     */
    public function setDataPath(DataSource $dataPath): self
    {
        $this->dataPath = $dataPath;
        return $this;
    }

    public function scheduleUnlock(): Content
    {
        $unlock = $this->getUnlock();
        $dataPath = $this->getDataPath();

        $result = (new UnlockHandler($dataPath))->create($unlock);

        return $result;
    }

    public function confirmUnlock(): Content
    {
        $unlock = $this->getUnlock();
        $dataPath = $this->getDataPath();

        $result = (new UnlockHandler($dataPath))->delete($unlock);

        return $result;
    }
}
