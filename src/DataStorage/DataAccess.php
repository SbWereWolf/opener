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
     * @param $requestText
     * @return bool
     */
    protected function processUpdate(\PDOStatement $request): DataAccess
    {
        $isSuccess = $request->execute();

        if ($isSuccess) {
            $this->setSuccessStatus();
        }

        if (!$isSuccess) {
            $this->setFailStatus();
        }

        return $this;
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

    protected function isSuccess()
    {
        return $this->status == true;

    }
}
