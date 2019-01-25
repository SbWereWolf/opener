<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 21:49
 */

namespace DataStorage\Basis;


class DataHandler
{
    protected $access = null;
    private $dsn = '';

    public function __construct(string $dataPath)
    {
        $this->dsn = "sqlite:$dataPath";
    }

    protected function begin(): bool
    {
        $result = $this->getAccess()->beginTransaction();

        return $result;
    }

    protected function getAccess(): \PDO
    {
        $access = $this->access;
        $isExists = !empty($access);

        if (!$isExists) {
            $access = new \PDO($this->dsn);
            $this->access = $access;
        }

        return $access;
    }

    protected function commit(): bool
    {
        $result = $this->getAccess()->commit();

        $this->dropAccess();

        return $result;
    }

    protected function dropAccess(): self
    {
        $this->access = null;

        return $this;
    }

    protected function rollBack(): bool
    {
        $result = $this->getAccess()->rollBack();

        $this->dropAccess();

        return $result;
    }
}
