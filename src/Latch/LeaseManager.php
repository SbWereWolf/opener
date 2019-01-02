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

    public function __construct(Lease $lease, string $dataPath)
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


    public function getLease():Lease
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

    public function read():ILeaseSet{

        $lease = $this->getLease();
        $isExists = !empty($lease->getToken());

        $dataPath = $this->getDataPath();
        $result = null;
        if($isExists){
            $result = (new LeaseHandler($dataPath))->getCurrent($lease);
        }
        if(!$isExists){
            $result = (new LeaseHandler($dataPath))->getActual();
        }

        return $result;
    }
    public function create():ILeaseSet{

        return new LeaseSet();
    }
    public function update():ILeaseSet{

        return new LeaseSet();
    }
}
