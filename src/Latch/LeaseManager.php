<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 18:47
 */

namespace Latch;


class LeaseManager
{
    private $lease = null;
    private $dataPath = '';

    public function __construct(ILease $lease, string $dataPath)
    {
        $this->setDataPath($dataPath)
            ->setLease($lease);
    }

    /**
     * @param null $lease
     * @return LeaseManager
     */
    public function setLease($lease)
    {
        $this->lease = $lease;
        return $this;
    }


    public function getLease(): ILease
    {
        return $this->lease;
    }

    /**
     * @param string $dataPath
     * @return LeaseManager
     */
    public function setDataPath(string $dataPath): LeaseManager
    {
        $this->dataPath = $dataPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataPath(): string
    {
        return $this->dataPath;
    }

    public function read(): Content
    {

        $lease = $this->getLease();
        $isExists = !empty($lease->getToken());

        $dataPath = $this->getDataPath();
        $result = new LeaseSet();
        if($isExists){
            $result = (new LeaseHandler($dataPath))->getCurrent($lease);
        }
        if(!$isExists){
            $result = (new LeaseHandler($dataPath))->getActual();
        }

        return $result;
    }

    public function create(): Content
    {

        $lease = $this->getLease();

        $dataPath = $this->getDataPath();
        $result = (new LeaseHandler($dataPath))->registerLease($lease);

        return $result;
    }

    public function update(): Content
    {

        $lease = $this->getLease();

        $dataPath = $this->getDataPath();
        $result = (new LeaseHandler($dataPath))->overrideLease($lease);

        return $result;
    }
}
