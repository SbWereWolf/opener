<?php

namespace DataStorage;


class DataAccess
{
    private $dsn = '';
    private $status = false;

    function __construct(string $dataPath)
    {
        $this->dsn = "sqlite:$dataPath";
        $this->setSuccessStatus();
    }

    /**
     * @return \PDO
     */
    protected function getDbConnection(): \PDO
    {
        $dbConnection = new \PDO($this->dsn);
        return $dbConnection;
    }

    protected function setSuccessStatus()
    {
        $this->status = true;
    }

    protected function setFailStatus()
    {
        $this->status = false;
    }

    public function isSuccess()
    {
        return $this->status == true;

    }

    public function isFail()
    {
        return $this->status == false;

    }

}
